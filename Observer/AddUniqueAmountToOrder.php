<?php

/**
 * Xtendable_UniqueTotal
 *
 * @see README.md
 *
 */

declare(strict_types=1);

namespace Xtendable\UniqueTotal\Observer;

use Xtendable\UniqueTotal\Helper\Data;
use Magento\Checkout\Model\Session;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

class AddUniqueAmountToOrder implements ObserverInterface
{
    /**
     * @var Session
     */
    protected $checkoutSession;

    /** @var Data  */
    protected $helper;
    
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * AddFeeToOrderObserver constructor.
     * @param Session $checkoutSession
     */
    public function __construct(
        Session $checkoutSession,
        Data $helper,
        LoggerInterface $loggerInterface
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->helper = $helper;
        $this->logger = $loggerInterface;
    }

    /**
     * Set payment fee to order
     *
     * @param EventObserver $observer
     * @return $this
     */
    public function execute(EventObserver $observer)
    {
        $quote = $observer->getQuote();
        $uniqueAmount = $this->helper->getUniqueAmount($quote);

        //Set fee data to order
        $order = $observer->getOrder();
        $order->setData('unique_amount', $uniqueAmount);
        $order->setData('base_unique_amount', $uniqueAmount);

        return $this;
    }
}
