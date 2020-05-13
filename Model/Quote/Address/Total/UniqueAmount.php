<?php

/**
 * Xtendable_UniqueTotal
 *
 * @see README.md
 *
 */

declare(strict_types=1);

namespace Xtendable\UniqueTotal\Model\Quote\Address\Total;

use Xtendable\UniqueTotal\Helper\Data;
use Magento\Checkout\Model\Session;
use Magento\Framework\Phrase;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address\Total;
use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;
use Magento\Quote\Model\QuoteValidator;
use Psr\Log\LoggerInterface;

class UniqueAmount extends AbstractTotal
{
    /**
     * @var string
     */
    protected $_code = Data::TOTAL_CODE;

    /**
     * @var Data
     */
    protected $helperData;

    /**
     * @var Session
     */
    protected $checkoutSession;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var QuoteValidator
     */
    protected $quoteValidator = null;

    /**
     * @param QuoteValidator  $quoteValidator
     * @param Session $checkoutSession
     * @param Data $helperData
     * @param LoggerInterface $loggerInterface
     */
    public function __construct(
        QuoteValidator $quoteValidator,
        Session $checkoutSession,
        Data $helperData,
        LoggerInterface $loggerInterface
    ) {
        $this->quoteValidator = $quoteValidator;
        $this->helperData = $helperData;
        $this->checkoutSession = $checkoutSession;
        $this->logger = $loggerInterface;
    }

    /**
     * Collect totals process.
     *
     * @param Quote $quote
     * @param ShippingAssignmentInterface $shippingAssignment
     * @param Total $total
     * @return $this
     */
    public function collect(
        Quote $quote,
        ShippingAssignmentInterface $shippingAssignment,
        Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);

        if (!count($shippingAssignment->getItems())) {
            return $this;
        }

        $unique = $this->helperData->getUniqueAmount($quote);

        $total->setUniqueAmount($unique);
        $total->setBaseUniqueAmount($unique);

        $total->setTotalAmount('unique_amount', $unique);
        $total->setBaseTotalAmount('base_unique_amount', $unique);

        $total->setGrandTotal($total->getGrandTotal());
        $total->setBaseGrandTotal($total->getBaseGrandTotal());

        // Make sure that quote is also updated
        $quote->setUniqueAmount($unique);
        $quote->setBaseUniqueAmount($unique);

        return $this;
    }

    /**
     * Assign subtotal amount and label to address object
     *
     * @param Quote $quote
     * @param Total $total
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function fetch(
        Quote $quote,
        Total $total
    ) {
        $result = [
            'code' => $this->getCode(),
            'title' => __($this->helperData->getTitle()),
            'value' => $total->getUniqueAmount()
        ];
        return $result;
    }

    /**
     * Get Subtotal label
     *
     * @return Phrase
     */
    public function getLabel()
    {
        return __($this->helperData->getTitle());
    }
}
