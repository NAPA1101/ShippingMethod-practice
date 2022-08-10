<?php
namespace Vendor\CustomShipping\Plugin;

use Magento\Sales\Block\Adminhtml\Order\Create\Shipping\Method\Form;

class ModifyCreateAddressFields
{
    public function __construct(Form $shippingMethod)
    {
        $this->shippingMethod = $shippingMethod;   
    }

    public function afterGetAttributes($subject, $result) {
        if(isset($result['middlename'])) {
            if($this->shippingMethod->getShippingMethod() == 'freeshipping_freeshipping') {
                $middle = $result['middlename'];
                $middle->setIsRequired(true);
            }
        }
        return $result;
    }
}
