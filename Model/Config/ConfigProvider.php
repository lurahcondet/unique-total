<?php

/**
 * Xtendable_UniqueTotal
 *
 * @see README.md
 *
 */

declare(strict_types=1);

namespace Xtendable\UniqueTotal\Model\Config;

use Xtendable\UniqueTotal\Helper\Data;
use Magento\Checkout\Model\ConfigProviderInterface;

/**
 * Class ConfigProvider
 *
 * @package Xtendable\UniqueTotal\Model\Config
 */
class ConfigProvider implements ConfigProviderInterface
{
    /**
     * @var Data
     */
    private $configHelper;

    /**
     * ConfigProvider constructor.
     *
     * @param Data $configHelper
     */
    public function __construct(Data $configHelper)
    {
        $this->configHelper = $configHelper;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return [
            'unique_total' => ['is_active' => $this->configHelper->isEnabled()],
        ];
    }
}
