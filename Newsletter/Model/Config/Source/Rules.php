<?php

declare(strict_types=1);

namespace Magedev\Newsletter\Model\Config\Source;

/**
 * @package Magedev\Newsletter\Model\Config\Source
 */
class Rules implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory
     */
    protected $_ruleCollectionFactory;

    /**
     * @param \Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory $ruleCollectionFactory
     */
    public function __construct(
        \Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory $ruleCollectionFactory
    ) {
        $this->_ruleCollectionFactory = $ruleCollectionFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        $rules = $this->_ruleCollectionFactory->create();
        $rules->addFieldToSelect('*')->addFieldToFilter('is_active', 1);

        $preparedRules = [];

        if (count($rules)) {
            foreach ($rules as $rule) {
                $preparedRules[] = [
                    'value' => $rule->getId(),
                    'label' => $rule->getName(),
                ];
            }
        }

        return $preparedRules;

    }


}
