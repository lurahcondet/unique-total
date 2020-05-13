<?php

/**
 * Xtendable_UniqueTotal
 *
 * @see README.md
 *
 */

declare(strict_types=1);

namespace Xtendable\UniqueTotal\Helper;

use InvalidArgumentException;
use Magento\Directory\Model\PriceCurrency;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Quote\Model\Quote;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    /**
     * Recipient fixed amount of custom payment config path
     */
    const CONFIG_PAYMENT_FEE = 'uniqueamount/setting/';

    /**
     * Total Code
     */
    const TOTAL_CODE = 'unique_amount';

    /**
     * Total Code
     */
    const CONFIG_TITLE = 'uniqueamount/setting/total_title';

    /**
     * @var array
     */
    public $methodFee = null;
    /**
     * Constructor
     */

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var Data
     */
    protected $pricingHelper;

    /**
     * @var PriceCurrency
     */
    protected $priceCurrency;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $json;

    /**
     * @var array
     */
    protected $currencies;

    /**
     * Data constructor.
     * @param Context $context
     * @param ObjectManagerInterface $objectManager
     * @param \Magento\Framework\Pricing\Helper\Data $pricingHelper
     * @param PriceCurrency $priceCurrency
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Pricing\Helper\Data $pricingHelper,
        \Magento\Framework\Serialize\Serializer\Json $json,
        PriceCurrency $priceCurrency
    ) {
        parent::__construct($context);
        $this->json = $json;
        $this->pricingHelper = $pricingHelper;
        $this->priceCurrency = $priceCurrency;
        $this->logger = $context->getLogger();
        $this->getUniqueDigit();
    }

    /**
     * Retrieve Payment Method Fees from Store Config
     * @return array
     */
    protected function getUniqueDigit()
    {
        if (!$this->currencies && $configValue = $this->getConfig('currency_digit')) {
            $configDigit = $this->json->unserialize($configValue);
            foreach ($configDigit as $config) {
                $this->currencies[$config['currency']] = $config;
            }
        }
    }

    /**
     * Retrieve Store Config
     * @param string $field
     * @return mixed|null
     */
    public function getConfig($field = '')
    {
        if ($field) {
            $storeScope = ScopeInterface::SCOPE_STORE;
            return $this->scopeConfig->getValue(self::CONFIG_PAYMENT_FEE . $field, $storeScope);
        }
        return null;
    }

    /**
     * Check if Extension is Enabled config
     * @return bool
     */
    public function isEnabled()
    {
        return $this->getConfig('enabled');
    }

    /**
     * get title
     * @return string
     */
    public function getTitle()
    {
        return $this->getConfig('total_title');
    }

    /**
     * @param Quote $quote
     * @return float|int
     */
    public function getUniqueAmount(Quote $quote)
    {
        if (!$this->isEnabled()) {
            return 0;
        }
        $method  = $quote->getPayment()->getMethod();
        $currency = $quote->getQuoteCurrencyCode();
        $allowedMethods = explode(",", $this->getConfig('payment_methods'));
        if (!in_array($method, $allowedMethods)) {
            return 0;
        }

        $decimal = $this->getConfig('default_type');
        $digit = $this->getConfig('default_digit');
        
        if ($this->currencies[$currency]) {
            $decimal = $this->currencies[$currency]['type'];
            $digit = $this->currencies[$currency]['digit'];
        }

        $currentAmount = fmod((float)$quote->getUniqueAmount(), 1);

        if ((!$decimal && (float)$quote->getUniqueAmount() > 0 && $currentAmount == 0)
        || ($decimal && $currentAmount > 0 && $currentAmount < 1 )) {
            return $quote->getUniqueAmount() ;
        }
        
        $max = str_pad("9", (int) $digit, "9");
        $diviser = 1;

        if ($decimal) {
            $diviser = str_pad("1", (int) $digit + 1, "0");
        }

        return rand(1, (int) $max) / (int) $diviser;
    }
}
