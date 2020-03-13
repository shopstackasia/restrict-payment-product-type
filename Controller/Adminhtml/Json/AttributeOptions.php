<?php

namespace Central\PaymentRestrictionProductType\Controller\Adminhtml\Json;

use Magento\Backend\App\Action;

class AttributeOptions extends \Magento\Backend\App\Action
{
    protected $eavConfig;

    public function __construct(Action\Context $context, \Magento\Eav\Model\Config $eavConfig)
    {
        parent::__construct($context);
        $this->eavConfig = $eavConfig;
    }

    public function execute()
    {
        $data = [];
        $attributeCode = $this->getRequest()->getParam('code');
        if ($attributeCode) {
            $attribute = $this->eavConfig->getAttribute('catalog_product', $attributeCode);
            $options = $attribute->getSource()->getAllOptions();
            array_shift($options);
            $data = $options;
        }

        $this->getResponse()->representJson(
            $this->_objectManager->get(\Magento\Framework\Json\Helper\Data::class)->jsonEncode($data)
        );
    }
}