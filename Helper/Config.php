<?php

namespace Central\PaymentRestrictionProductType\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Config extends AbstractHelper
{
    const CONFIG_ENABLE_MODULE = 'payment_restriction_product_type/payment_restriction/enabled';
    const CONFIG_PAYMENT_RESTRICTION = 'payment_restriction_product_type/payment_restriction/product_attribute';
    const CONFIG_PAYMENT_COD_RESTRICTION = 'payment_restriction_product_type/payment_restriction/restrict_value';
    const CONFIG_PAYMENT_COD_METHOD = 'payment_restriction_product_type/payment_restriction/payment';

    /**
     * @param $path
     * @return mixed
     */
    public function getConfig($path, $storeId = null)
    {
        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @return mixed
     */
    public function enabledModule()
    {
        return $this->getConfig(self::CONFIG_ENABLE_MODULE);
    }

    public function getProductAttribute()
    {
        return $this->getConfig(self::CONFIG_PAYMENT_RESTRICTION);
    }

    public function getCodRestrictOptions()
    {
        return $this->getConfig(self::CONFIG_PAYMENT_COD_RESTRICTION);
    }

    public function getCodMethod()
    {
        return \Magento\OfflinePayments\Model\Cashondelivery::PAYMENT_METHOD_CASHONDELIVERY_CODE;
    }
}