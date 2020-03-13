<?php

namespace Central\PaymentRestrictionProductType\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class ProductAttributes implements OptionSourceInterface
{
    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;
    /**
     * @var \Magento\Eav\Api\AttributeRepositoryInterface
     */
    protected $attributeRepository;

    public function __construct(\Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
                                \Magento\Eav\Api\AttributeRepositoryInterface $attributeRepository)
    {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->attributeRepository = $attributeRepository;
    }

    public function toOptionArray()
    {
        return $this->getSourceOptions();
    }

    private function getSourceOptions(): array
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('is_user_defined', 1)
            ->addFilter('frontend_input', ['select', 'multiline'], 'in')
            ->create();

        $attributeRepository = $this->attributeRepository->getList(
            'catalog_product',
            $searchCriteria
        );

        $sources[] = ['label' => '', 'value' => ''];
        foreach ($attributeRepository->getItems() as $items) {
            $sources[] = ['label' => $items->getFrontendLabel(), 'value' => $items->getAttributeCode()];
        }

        return $sources;
    }
}