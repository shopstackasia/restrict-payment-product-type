<?php

namespace Central\PaymentRestrictionProductType\Plugin\Model\Method;

use Central\PaymentRestrictionProductType\Helper\Config;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Store\Model\StoreManagerInterface;

class Available
{
    /**
     * @var \Central\PaymentRestrictionProductType\Helper\Config
     */
    private $config;

    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    public function __construct(Config $config, CheckoutSession $session, StoreManagerInterface $storeManager)
    {
        $this->config = $config;
        $this->checkoutSession = $session;
        $this->storeManager = $storeManager;
    }

    private function getQuote()
    {
        return $this->checkoutSession->getQuote();
    }

    public function afterGetAvailableMethods($subject, $result)
    {
        if ($this->config->enabledModule()) {
            $codMethod = $this->config->getCodMethod();
            if ($codMethod && !$this->checkCondition()) {
                foreach ($result as $key => $_result) {
                    if ($_result->getCode() == $codMethod) {
                        $isAllowed = false;
                        if (!$isAllowed) {
                            unset($result[$key]);
                        }
                    }
                }
            }
        }
        return $result;
    }

    public function checkCondition()
    {
        if ($this->config->enabledModule()) {
            $productAttributeCode = $this->config->getProductAttribute();
            $codRestrictOptions = $this->config->getCodRestrictOptions();
            if ($productAttributeCode && $codRestrictOptions) {
                $allOptions = $this->getQuote()->getAllVisibleItems();
                $allowCod = 0;
                foreach ($allOptions as $item) {
                    $product = $item->getProduct();
                    $optionValue = $product->getResource()
                        ->getAttributeRawValue(
                            $product->getId(),
                            $productAttributeCode,
                            $this->storeManager->getStore()->getId()
                        );
                    $restrictOptionsData = explode(',', $codRestrictOptions);
                    if (in_array($optionValue, $restrictOptionsData)) {
                        $allowCod++;
                    }
                }

                return count($allOptions) == $allowCod;
            }
        }

        return false;
    }
}