<?php

declare(strict_types=1);

namespace Magedev\Newsletter\Observer;

use Magento\Framework\Event\Observer;

/**
 * @package Magedev\Newsletter\Observer
 */
class Coupon implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * @var \Magedev\Newsletter\Block\Coupon
     */
    private $coupon;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    public $logger;


    /**
     *
     * @param \Magedev\Newsletter\Block\Coupon $coupon
     * @param \Psr\Log\LoggerInterface         $logger
     */
    public function __construct(
        \Magedev\Newsletter\Block\Coupon $coupon,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->coupon = $coupon;
        $this->logger = $logger;

    }


    public function execute(Observer $observer)
    {
        $customer = $observer->getEvent()->getCustomer();

        $subscriberStatus = $customer->getExtensionAttributes()->getIsSubscribed();

        if ($subscriberStatus === true) {
            $genericCoupon = $this->coupon->getCoupon();
            $this->logger->notice('coupon send to subscriber: '
                . $customer->getEmail() . 'and have coupon: '
                . $genericCoupon['0']
        );
        }
    }


}
