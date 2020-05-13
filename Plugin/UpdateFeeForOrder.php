<?php

/**
 * Xtendable_UniqueTotal
 *
 * @see README.md
 *
 */

declare(strict_types=1);

namespace Xtendable\UniqueTotal\Plugin;

use Xtendable\UniqueTotal\Helper\Data;
use Magento\Checkout\Model\Session;
use Magento\Framework\Registry;
use Magento\Paypal\Model\Cart;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\QuoteFactory;
use Psr\Log\LoggerInterface;

class UpdateFeeForOrder
{
    /**
     * @var QuoteFactory
     */
    protected $quote;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Session
     */
    protected $checkoutSession;

    /**
     * @var Registry
     */
    protected $registry;

    /** @var Data  */
    protected $helper;

    /**
     * UpdateFeeForOrder constructor.
     * @param Quote $quote
     * @param LoggerInterface $logger
     * @param Session $checkoutSession
     * @param Registry $registry
     */
    public function __construct(
        Quote $quote,
        LoggerInterface $logger,
        Session $checkoutSession,
        Registry $registry,
        Data $helper
    ) {
        $this->quote = $quote;
        $this->logger = $logger;
        $this->checkoutSession = $checkoutSession;
        $this->registry = $registry;
        $this->helper = $helper;
    }

    /**
     * Add unique amount as a custom line item
     *
     * @param Cart $cart
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function beforeGetAllItems(Cart $cart)
    {
        $quote = $this->checkoutSession->getQuote();
        $paymentMethod = $quote->getPayment()->getMethod();

        $paypalMehodList = ['payflowpro','payflow_link','payflow_advanced','braintree_paypal','paypal_express_bml','payflow_express_bml','payflow_express','paypal_express'];
        if (!in_array($paymentMethod, $paypalMehodList)) {
            return;
        }

        $uniqueAmount = $quote->getUniqueAmount();
        $cart->addCustomItem(__($this->helper->getTitle()), 1, $uniqueAmount, Data::TOTAL_CODE);
        $cart->addSubtotal($uniqueAmount);
    }

    /**
     * Get shipping, tax, subtotal and discount amounts all together
     * No way to tell if we already added a unique amount line item in beforeGetAllItems :'(
     * We will filter out any extras
     *
     * @param Cart $cart
     * @param $result
     * @return array
     */
    public function afterGetAllItems(Cart $cart, $result)
    {
        if (empty($result)) {
            return $result;
        }

        $found = false;
        foreach ($result as $key => $item) {
            if ($item->getId() != Data::TOTAL_CODE) {
                continue;
            }

            if ($found) {
                unset($result[$key]);
                continue;
            }

            $found = true;
        }

        return $result;
    }
}
