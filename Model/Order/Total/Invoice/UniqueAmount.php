<?php

/**
 * Xtendable_UniqueTotal
 *
 * @see README.md
 *
 */

declare(strict_types=1);

namespace Xtendable\UniqueTotal\Model\Order\Total\Invoice;

use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Order\Invoice\Total\AbstractTotal;
use Psr\Log\LoggerInterface;

class UniqueAmount extends AbstractTotal
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Invoice Unique Amount constructor.
     * @param LoggerInterface $loggerInterface
     */
    public function __construct(
        LoggerInterface $loggerInterface
    ) {
        $this->logger = $loggerInterface;
    }

    /**
     * Collect invoice subtotal
     *
     * @param Invoice $invoice
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function collect(Invoice $invoice)
    {
        $order = $invoice->getOrder();
        $uniqueAmount = $order->getUniqueAmount();
        $baseUniqueAmount = $order->getBaseUniqueAmount();

        $invoice->setUniqueAmount($uniqueAmount);
        $invoice->setBaseUniqueAmount($baseUniqueAmount);
        $invoice->setGrandTotal($invoice->getGrandTotal() + $uniqueAmount);
        $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $baseUniqueAmount);

        $order->setUniqueAmountInvoiced($uniqueAmount);
        $order->setBaseUniqueAmountInvoiced($baseUniqueAmount);

        return $this;
    }
}
