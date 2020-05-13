<?php

/**
 * Xtendable_UniqueTotal
 *
 * @see README.md
 *
 */

declare(strict_types=1);

namespace Xtendable\UniqueTotal\Block\Adminhtml\Form\Field\Decimal;

/**
 * Source
 * @SuppressWarnings(PHPMD)
 */
class Source extends \Magento\Backend\Block\Template
{
    /**
     * {@inheritdoc}
     */
    protected function _toHtml()
    {
        $inputId = $this->getInputId();
        $inputName = $this->getInputName();
        $colName = $this->getColumnName();
        $column = $this->getColumn();
    
        $string = '<select id="' . $inputId . '"' .
            ' name="' . $inputName . '" <%- ' . $colName . ' %> ' .
            ($column['size'] ? 'size="' . $column['size'] . '"' : '') .
            ' class="' . (isset($column['class']) ? $column['class'] : 'input-text') . '">';
            $string .= '<option value="0">' . __('No') . '</option>';
            $string .= '<option value="1">' . __('Yes') . '</option>';
        $string .= '</select>';
        return $string;
    }
}
