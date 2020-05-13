<?php

/**
 * Xtendable_UniqueTotal
 *
 * @see README.md
 *
 */

declare(strict_types=1);

namespace Xtendable\UniqueTotal\Block\Adminhtml\Sales\Order\Creditmemo;

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

    public function getCreditmemo()
    {
        return $this->getParentBlock()->getCreditmemo();
    }
    /**
     * Initialize payment unique totals
     *
     * @return $this
     */
    public function initTotals()
    {
        $this->getParentBlock();
        $this->getCreditmemo();
        $this->getSource();

        if ((float)$this->getSource()->getUniqueAmount() == 0) {
            return $this;
        }

        $title = $this->_scopeConfig->getValue(\Xtendable\UniqueTotal\Helper\Data::CONFIG_TITLE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $unique = new DataObject(
            [
                'code' => \Xtendable\UniqueTotal\Helper\Data::TOTAL_CODE,
                'strong' => false,
                'value' => $this->getSource()->getUniqueAmount(),
                'label' => __($title),
            ]
        );

        $this->getParentBlock()->addTotalBefore($unique, 'grand_total');

        return $this;
    }
}
