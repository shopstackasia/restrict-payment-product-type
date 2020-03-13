<?php

namespace Central\PaymentRestrictionProductType\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Payment\Helper\Data;

class PaymentMethod implements OptionSourceInterface
{
    /**
     * @var Data
     */
    protected $paymentHelper;

    /**
     * PaymentMethod constructor.
     *
     * @param Data $paymentHelper
     */
    public function __construct(Data $paymentHelper)
    {
        $this->paymentHelper = $paymentHelper;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $arrPayment = [];
        foreach ($this->paymentHelper->getPaymentMethodList() as $code => $label) {
            if (!empty($label)) {
                $arrPayment[] = ['value' => $code, 'label' => $label];
            }
        }

        return $arrPayment;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        $arrPayment = [];

        foreach ($this->paymentHelper->getPaymentMethodList() as $code => $label) {
            if (!empty($label)) {
                $arrPayment[$code] = $label;
            }
        }

        return $arrPayment;
    }
}