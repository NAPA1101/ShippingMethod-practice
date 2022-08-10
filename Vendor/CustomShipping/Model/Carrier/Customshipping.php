<?php

namespace Vendor\CustomShipping\Model\Carrier;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Vendor\CustomShipping\Helper\Data;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Psr\Log\LoggerInterface;
use Magento\Shipping\Model\Rate\ResultFactory;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;


/**
 * Custom shipping model
 */
class Customshipping extends AbstractCarrier implements CarrierInterface
{
    /**
     * @var string
     */
    protected $_code = 'customshipping';

    /**
     * @var bool
     */
    protected $_isFixed = true;

    /**
     * @var Data;
     */
    protected $helperData;

    /**
     * @var ResultFactory
     */
    protected $rateResultFactory;

    /**
     * @var MethodFactory
     */
    protected $rateMethodFactory;

    

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param ErrorFactory $rateErrorFactory
     * @param LoggerInterface $logger
     * @param ResultFactory $rateResultFactory
     * @param MethodFactory $rateMethodFactory
     * @param array $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ErrorFactory $rateErrorFactory,
        LoggerInterface $logger,
        ResultFactory $rateResultFactory,
        MethodFactory $rateMethodFactory,
        Data $helperData,
        
        array $data = []
    ) {
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);

        $this->rateResultFactory = $rateResultFactory;
        $this->rateMethodFactory = $rateMethodFactory;
        $this->helperData = $helperData;
        
    }

    /**
     * Custom Shipping Rates Collector
     *
     * @param RateRequest $request
     * @return \Magento\Shipping\Model\Rate\Result|bool
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        } else {
            return null;
        }

        $quote = null;
        $items = $request->getAllItems();
        foreach($items as $item) {
            $quote = $item->getQuote();
            break;
        }

        $shippingAddress = $quote->getShippingAddress();
        
        if ($shippingAddress->getCompany() == null) {
            return false;
        } else {
            return null;
        }

        /** @var \Magento\Shipping\Model\Rate\Result $result */
        $result = $this->rateResultFactory->create();

        /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $method */
        $method = $this->rateMethodFactory->create();

        $method->setCarrier($this->_code);
        $method->setCarrierTitle($this->getConfigData('title'));

        $method->setMethod($this->_code);
        $method->setMethodTitle($this->getConfigData('name'));

        $shippingCost = (float)$this->getConfigData('shipping_cost');
        
        if ($this->helperData->getGeneralConfig('enabled_countries')) {
            $discount = $this->helperData->getGeneralConfig('percent');
            $countries = explode(',', $this->helperData->getCountries());

            if (in_array($request->getDestCountryId(), $countries)) {
                $shippingCost = $shippingCost * (100 - $discount) / 100;
            } else {
                return null;
            }
        } else {
            return null;
        }

        if ($this->helperData->getGeneralConfig('enabled_company')) {
            $companies = explode(', ', $this->helperData->getCompanies());

            if (in_array($shippingAddress->getCompany(), $companies)) {
                $shippingCost = 0;
            } else {
                return null;
            }
        } else {
            return null;
        }

        $method->setPrice($shippingCost);
        $method->setCost($shippingCost);

        $result->append($method);

        return $result;
    }

    /**
     * @return array
     */
    
    public function getAllowedMethods()
    {
        return [$this->_code => $this->getConfigData('name')];
    }
}