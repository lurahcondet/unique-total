<?php

/**
 * Xtendable_UniqueTotal
 *
 * @see README.md
 *
 */

declare(strict_types=1);

namespace Xtendable\UniqueTotal\Plugin;

use Xtendable\UniqueTotal\Helper\Data;

class LayoutProcessor
{

    /**
    * @var Data
    */
    protected $helper;

    /**
     * @param Data $helper
     */
    public function __construct(
        Data $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $subject
     * @param array $jsLayout
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    // phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter
    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array  $jsLayout
    ) {

        $jsLayout['components']['checkout']['children']['sidebar']['children']['summary']['children']['totals']['children']['uniqueamount']['config']['title'] = $this->helper->getTitle();
        return $jsLayout;
    }
}
