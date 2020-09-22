<?php

declare(strict_types=1);

namespace Magedev\Newsletter\Block;

/**
 * Class Coupon
 */
class Coupon extends \Magento\Framework\View\Element\Template
{
    const RULE_ID = 'magedev/newsletter/rule_id';

    const COUPON_LENGTH = 10;

    const COUPON_QTY = 1;

    const COUPON_FORMAT = 'alphanum';

    /**
     * @var \Magento\SalesRule\Model\CouponGenerator
     */
    public $couponGenerator;

    /**
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\SalesRule\Model\CouponGenerator $couponGenerator
     * @param \Magento\SalesRule\Model\Rule $rule
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\SalesRule\Model\CouponGenerator $couponGenerator,
        \Magento\SalesRule\Model\Rule $rule,
        array $data=[]
    ) {
        parent::__construct($context, $data);
        $this->couponGenerator = $couponGenerator;

    }

    /**
     * @return string[]
     */
    public function getCoupon(): array
    {
        return $this->generateCoupon();
    }

    /**
     * @return string[]
     */
    private function generateCoupon(): array
    {
        $data = [
            'rule_id' => $this->_scopeConfig->getValue(self::RULE_ID),
            'qty'     => self::COUPON_QTY,
            'length'  => self::COUPON_LENGTH,
            'format'  => self::COUPON_FORMAT,
        ];
        return $this->couponGenerator->generateCodes($data);
    }


}
