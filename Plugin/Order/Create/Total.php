<?php

/**
 * Xtendable_UniqueTotal
 *
 * @see README.md
 *
 */

declare(strict_types=1);

namespace Xtendable\UniqueTotal\Plugin\Order\Create;

use Magento\Sales\Block\Adminhtml\Order\Create\Totals;
use Xtendable\UniqueTotal\Helper\Data;

class Total
{
    /**
     * @param Totals $subject
     * @param array $result
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    // phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter
    public function afterGetTotals(
        Totals $subject,
        array  $result
    ) {
        $newResults = [];
        foreach ($result as $total) {
            if ($total->getCode() == Data::TOTAL_CODE && (float)$total->getValue() == 0) {
                continue;
            }
            $newResults[] = $total;
        }
        return $newResults;
    }
}
