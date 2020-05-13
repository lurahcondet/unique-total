<?php

/**
 * Xtendable_UniqueTotal
 *
 * @see README.md
 *
 */

declare(strict_types=1);

namespace Xtendable\UniqueTotal\Block\Sales;

use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Template;
use Magento\Sales\Model\Order;

class Totals extends Template
{
    /**
     * @var Order
     */
    protected $_order;

    /**
     * @var DataObject
     */
    protected $_source;

    /**
     * Check if we nedd display full tax total info
     *
     * @return bool
     */
    public function displayFullSummary()
    {
        return true;
    }

    /**
     * Get data (totals) source model
     *
     * @return DataObject
     */
    public function getSource()
    {
        return $this->_source;
    }
    public function getStore()
    {
        return $this->_order->getStore();
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->_order;
    }
    
    /**
     * Initialize unique amount totals
     *
     * @return $this
     */
    public function initTotals()
    {
        $parent = $this->getParentBlock();
        $this->_order = $parent->getOrder();
        $this->_source = $parent->getSource();
        if ($this->_source->getUniqueAmount() == 0) {
            return $this;
        }

        $title = $this->_scopeConfig->getValue(\Xtendable\UniqueTotal\Helper\Data::CONFIG_TITLE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $total = new DataObject(
            [
                'code' => \Xtendable\UniqueTotal\Helper\Data::TOTAL_CODE,
                'strong' => false,
                'value' => $this->_source->getUniqueAmount(),
                'label' => __($title),
            ]
        );

        $parent->addTotal($total, 'unique');

        return $this;
    }
}
