<?php
namespace Vendor\CustomShipping\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{

    protected $scopeConfig;

    const XML_PATH_COUNTRIES = 'carriers/customshipping/countries_discount';
    const XML_PATH_COMPANIES = 'carriers/customshipping/free_company';
    const XML_PATH_CARRIERS = 'carriers/';


    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
   {
      $this->scopeConfig = $scopeConfig;
   }

    public function getConfigValue(string $field, int $storeId = null) : int
    {
        return $this->scopeConfig->getValue(
            $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getGeneralConfig(string $code, int $storeId = null) : int
    {
        return $this->getConfigValue(
            self::XML_PATH_CARRIERS .'customshipping/'. $code, $storeId
        );
    }

    public function getCountries() {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->getValue(self::XML_PATH_COUNTRIES, $storeScope);
    }

    public function getCompanies() {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->getValue(self::XML_PATH_COMPANIES, $storeScope);
    }

};