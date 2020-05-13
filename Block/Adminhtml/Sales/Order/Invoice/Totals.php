<?php

/**
 * Xtendable_UniqueTotal
 *
 * @see README.md
 *
 */

declare(strict_types=1);

namespace Xtendable\UniqueTotal\Block\Adminhtml\Sales\Order\Invoice;

use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Template;

class Totals extends Template
{

    /**
     * Get data (totals) source model
     *
     * @return DataObject
     */
    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    /**
     * @return mixed
     */
    public function getInvoice()
    {
        return $this->getParentBlock()->getInvoice();
    }
    /**
     * Initialize payment fee totals
     *
     * @return $this
     */
    public function initTotals()
    {
        $this->getParentBlock();
        $this->getInvoice();
        $this->getSource();

        if ((float)$this->getSource()->getUniqueAmount() == 0) {
            return $this;
        }
        $title = $this->_scopeConfig->getValue(\Xtendable\UniqueTotal\Helper\Data::CONFIG_TITLE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $total = new DataObject(
            [
                'code' => \Xtendable\UniqueTotal\Helper\Data::TOTAL_CODE,
                'value' => $this->getSource()->getUniqueAmount(),
                'label' => __($title),
            ]
        );

        $this->getParentBlock()->addTotalBefore($total, 'grand_total');
        return $this;
    }
}
