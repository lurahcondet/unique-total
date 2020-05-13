<?php

/**
 * Xtendable_UniqueTotal
 *
 * @see README.md
 *
 */

declare(strict_types=1);

namespace Xtendable\UniqueTotal\Block\Adminhtml\Sales\Order;

use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Template;
use Magento\Sales\Model\Order;

class Totals extends Template
{

    /**
     * Retrieve current order model instance
     *
     * @return Order
     */
    public function getOrder()
    {
        return $this->getParentBlock()->getOrder();
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    /**
     * @return $this
     */
    public function initTotals()
    {
        $this->getParentBlock();
        $this->getOrder();
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
