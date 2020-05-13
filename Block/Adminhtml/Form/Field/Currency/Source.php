<?php

/**
 * Xtendable_UniqueTotal
 *
 * @see README.md
 *
 */

declare(strict_types=1);

namespace Xtendable\UniqueTotal\Block\Adminhtml\Form\Field\Currency;

/**
 * Source
 * @SuppressWarnings(PHPMD)
 */
class Source extends \Magento\Backend\Block\Template
{
    /**
     * @var \Magento\Framework\Locale\ListsInterface
     */
    protected $localeLists;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Locale\ListsInterface $localeLists
     * @param mixed $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Locale\ListsInterface $localeLists,
        array $data = []
    ) {
        $this->localeLists = $localeLists;
        parent::__construct($context, $data);
    }

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
        foreach ($this->localeLists->getOptionCurrencies() as $data) {
            $string .= '<option value="' . $data['value']. '">' . $data['label'] . '</option>';
        }
        $string .= '</select>';
        return $string;
    }
}
