<?php

/**
 * Xtendable_UniqueTotal
 *
 * @see README.md
 *
 */

declare(strict_types=1);

namespace Xtendable\UniqueTotal\Controller\Checkout;

use Exception;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\Raw;
use Magento\Framework\Json\Helper\Data;
use Magento\Quote\Api\CartRepositoryInterface;
use Psr\Log\LoggerInterface;

class Totals extends Action
{
    /**
     * @var Session
     */
    protected $checkoutSession;

    /**
     * @var JsonFactory
     */
    protected $resultJson;

    /**
     * @var Data
     */
    protected $helper;
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var CartRepositoryInterface
     */
    protected $quoteRepository;

    public function __construct(
        Context $context,
        Session $checkoutSession,
        Data $helper,
        JsonFactory $resultJson,
        CartRepositoryInterface $quoteRepository,
        LoggerInterface $loggerInterface
    ) {
        parent::__construct($context);
        $this->checkoutSession = $checkoutSession;
        $this->helper = $helper;
        $this->resultJson = $resultJson;
        $this->quoteRepository = $quoteRepository;
        $this->logger = $loggerInterface;
    }

    /**
     * Trigger to re-calculate the collect Totals
     *
     * @return Json
     */
    public function execute()
    {
        $response = [
            'errors' => false,
            'message' => 'Re-calculate successful.'
        ];
        try {
            $this->quoteRepository->get($this->checkoutSession->getQuoteId());
            $quote = $this->checkoutSession->getQuote();

            //Trigger to re-calculate totals
            $payment = $this->helper->jsonDecode($this->getRequest()->getContent());
            $this->checkoutSession->getQuote()->getPayment()->setMethod($payment['payment']);
            $quote->collectTotals();
            $this->quoteRepository->save($quote);
        } catch (Exception $e) {
            $response = [
                'errors' => true,
                'message' => $e->getMessage()
            ];
        }

        /** @var Raw $resultRaw */
        $resultJson = $this->resultJson->create();
        return $resultJson->setData($response);
    }
}
