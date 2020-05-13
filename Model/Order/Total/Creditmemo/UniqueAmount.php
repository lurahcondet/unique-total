<?php

/**
 * Xtendable_UniqueTotal
 *
 * @see README.md
 *
 */

declare(strict_types=1);

namespace Xtendable\UniqueTotal\Model\Order\Total\Creditmemo;

use Magento\Sales\Model\Order\Creditmemo;
use Magento\Sales\Model\Order\Creditmemo\Total\AbstractTotal;
use Psr\Log\LoggerInterface;

class UniqueAmount extends AbstractTotal
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Credit Memo Fee constructor.
     * @param LoggerInterface $loggerInterface
     */
    public function __construct(
        LoggerInterface $loggerInterface
    ) {
        $this->logger = $loggerInterface;
    }

    /**
     * @param Creditmemo $creditmemo
     * @return $this
     */
    public function collect(Creditmemo $creditmemo)
    {
        $order = $creditmemo->getOrder();

        $uniqueAmountInvoiced = $order->getUniqueAmountInvoiced();
        $baseUniqueAmountInvoiced = $order->getBaseUniqueAmountInvoiced();

        // Nothing to refound
        if ((int)$uniqueAmountInvoiced === 0) {
            return $this;
        }

        // Check if refound has already been done
        $uniqueAmountRefunded = $order->getUniqueAmountRefunded();
        if ((int)$uniqueAmountRefunded === 0) {
            $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $uniqueAmountInvoiced);
            $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $baseUniqueAmountInvoiced);
            $creditmemo->setUniqueAmount($uniqueAmountInvoiced);
            $creditmemo->setBaseUniqueAmount($baseUniqueAmountInvoiced);

            // Set unique amount refunded into order
            $order->setUniqueAmountRefunded($uniqueAmountInvoiced);
            $order->setBaseUniqueAmountRefunded($baseUniqueAmountInvoiced);
        }

        return $this;
    }
}
