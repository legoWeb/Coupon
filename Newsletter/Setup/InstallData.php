<?php

declare(strict_types=1);

namespace Magedev\Newsletter\Setup;

/**
 * @package Magedev\Newsletter\Setup
 */
class InstallData implements \Magento\Framework\Setup\InstallDataInterface
{
    /**
     * @var \Magento\SalesRule\Api\Data\RuleInterfaceFactory
     */
    protected $_cartPriceRuleFactory;

    /**
     * @var \Magento\Framework\App\State
     */
    protected $_appState;

    /**
     * @var \Magento\SalesRule\Api\RuleRepositoryInterface
     */
    protected $_ruleRepository;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Group\Collection
     */
    protected $_customerGroup;

    /**
     * @var \Magento\Framework\App\Config\Storage\WriterInterface
     */
    protected $_configWriter;

    /**
     * InstallData constructor.
     * @param \Magento\SalesRule\Api\Data\RuleInterfaceFactory $cartPriceRuleFactory
     * @param \Magento\Framework\App\State $appState
     * @param \Magento\SalesRule\Api\RuleRepositoryInterface $ruleRepository
     * @param \Magento\Customer\Model\ResourceModel\Group\Collection $customerGroup
     * @param \Magento\Framework\App\Config\Storage\WriterInterface $configWriter
     */
    public function __construct(
        \Magento\SalesRule\Api\Data\RuleInterfaceFactory $cartPriceRuleFactory,
        \Magento\Framework\App\State $appState,
        \Magento\SalesRule\Api\RuleRepositoryInterface $ruleRepository,
        \Magento\Customer\Model\ResourceModel\Group\Collection $customerGroup,
        \Magento\Framework\App\Config\Storage\WriterInterface $configWriter
    ) {
        $this->_cartPriceRuleFactory = $cartPriceRuleFactory;
        $this->_appState = $appState;
        $this->_ruleRepository = $ruleRepository;
        $this->_customerGroup = $customerGroup;
        $this->_configWriter = $configWriter;
    }

    /**
     * @param \Magento\Framework\Setup\ModuleDataSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     */
    public function install(
        \Magento\Framework\Setup\ModuleDataSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        $this->_addCartPriceRule();
    }

    /**
     * @throws \Exception
     */
    protected function _addCartPriceRule()
    {
        $cartPriceRule = $this->_cartPriceRuleFactory->create();
        $cartPriceRule->setName('10% After Subscribe')
        ->setDescription("10% After Subscribe")
        ->setIsAdvanced(true)
        ->setCustomerGroupIds($this->_getCustomerGroupIds())
        ->setWebsiteIds([1])
        ->setUsesPerCoupon(1)
        ->setCouponType(\Magento\SalesRule\Api\Data\RuleInterface::COUPON_TYPE_SPECIFIC_COUPON)
        ->setSimpleAction(\Magento\SalesRule\Api\Data\RuleInterface::DISCOUNT_ACTION_BY_PERCENT)
        ->setDiscountAmount(10)
        ->setIsActive(true)
        ->setUsesPerCustomer(1)
        ->setUseAutoGeneration(true);

        $rule = $this->_appState->emulateAreaCode(
            \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE,
            [$this->_ruleRepository, 'save'],
            [$cartPriceRule]
        );

        $this->_configWriter->save(
            'magedev/newsletter/rule_id',
            $rule->getRuleId(),
            \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            0
        );
    }

    /**
     * @return array
     */
    protected function _getCustomerGroupIds()
    {
        $preparedCustomerGroupIds = [];
        foreach ($this->_customerGroup as $customerGroup) {
            $preparedCustomerGroupIds[] = $customerGroup->getId();
        }

        return $preparedCustomerGroupIds;
    }
}
