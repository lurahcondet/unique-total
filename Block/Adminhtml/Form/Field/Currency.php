<?php

/**
 * Xtendable_UniqueTotal
 *
 * @see README.md
 *
 */

declare(strict_types=1);

namespace Xtendable\UniqueTotal\Block\Adminhtml\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Xtendable\UniqueTotal\Block\Adminhtml\Form\Field\Currency\SourceFactory as CurrencyFactory;
use Xtendable\UniqueTotal\Block\Adminhtml\Form\Field\Decimal\SourceFactory as DecimalFactory;

/**
 * Class Currency
 * @SuppressWarnings(PHPMD)
 */
class Currency extends AbstractFieldArray
{
    /**
     * @var CurrencyFactory
     */
    protected $currency;

    /**
     * @var DecimalFactory
     */
    protected $decimal;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param CurrencyFactory $currency
     * @param DecimalFactory $decimal
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        CurrencyFactory $currency,
        DecimalFactory $decimal,
        array $data = []
    ) {
        $this->currency = $currency;
        $this->decimal = $decimal;
        parent::__construct($context, $data);
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareToRender()
    {
        $this->addColumn(
            'currency',
            [
                'label' => __('Currency'),
                'class' => 'required-entry',
                'renderer' => $this->currency->create()
            ]
        );
        $this->addColumn(
            'digit',
            [
                'label' => __('Digit Number'),
                'class' => 'required-entry validate-number'
            ]
        );
        $this->addColumn(
            'type',
            [
                'label' => __('Is Decimal Point?'),
                'class' => 'required-entry',
                'renderer' => $this->decimal->create()
            ]
        );
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }
}
