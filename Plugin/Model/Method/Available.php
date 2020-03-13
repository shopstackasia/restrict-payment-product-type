<?php

namespace Central\PaymentRestrictionProductType\Plugin\Model\Method;

use Central\PaymentRestrictionProductType\Helper\Config;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Session as CheckoutSession;

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
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    public function __construct(Config $config, CheckoutSession $session, ProductRepositoryInterface $productRepository)
    {
        $this->config = $config;
        $this->checkoutSession = $session;
        $this->productRepository = $productRepository;
    }

    private function getQuote()
    {
        return $this->checkoutSession->getQuote();
    }

    public function afterGetAvailableMethods($subject, $result)
    {
        $codMethod = $this->config->getCodMethod();
        if ($codMethod && $this->checkCondition()) {
            foreach ($result as $key => $_result) {
                if ($_result->getCode() == $codMethod) {
                    $isAllowed = false; // your logic here
                    if (!$isAllowed) {
                        unset($result[$key]);
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
                    $product = $this->productRepository->get($item->getSku());
                    $productOptionValue = $product->getData($productAttributeCode);
                    $restrictOptionsData = explode(',', $codRestrictOptions);
                    if (in_array($productOptionValue, $restrictOptionsData)) {
                        $allowCod++;
                    }
                }

                return count($allOptions) == $allowCod;
            }
        }

        return false;
    }
}