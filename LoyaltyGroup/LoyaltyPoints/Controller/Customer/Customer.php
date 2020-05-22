<?php

namespace LoyaltyGroup\LoyaltyPoints\Controller\Customer;

use Magento\Customer\Model\Session;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NotFoundException;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;

class Customer extends Action
{

    protected $session;
    protected $cartRepository;
    protected $checkoutSession;

    /**
     * Customer constructor.
     * @param Context $context
     * @param Session $session
     * @param CartRepositoryInterface $cartRepository
     * @param CheckoutSession $checkoutSession
     * @param CookieManagerInterface $cookieManager
     * @param CookieMetadataFactory $cookieMetadataFactory
     * @param SessionManagerInterface $sessionManager
     */
    public function __construct(
        Context $context,
        Session $session,
        CartRepositoryInterface $cartRepository,
        CheckoutSession $checkoutSession,
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory,
        SessionManagerInterface $sessionManager
    ) {
        $this->session = $session;
        $this->cartRepository = $cartRepository;
        $this->checkoutSession = $checkoutSession;
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->sessionManager = $sessionManager;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        try {
            $this->_validateRequest();
            $request = $this->getRequest()->getParam('test');
            $this->session->setUsePoints($request);
            $this->setCookie('usePointsOrNot', $request);
            if ($request == 1){
                $this->setCookie('checkoutPosition', true);
            } else $this->setCookie('checkoutPosition', false);
            $quoteId = $this->checkoutSession->getQuoteId();
            $quote = $this->cartRepository->get($quoteId);
            $quote->collectTotals()->save();
        } catch (\Exception $exception) {
            return $result->setData($exception->getMessage());
        }
        return $result->setData($request);
    }

    /**
     * @throws NotFoundException
     */
    protected function _validateRequest()
    {
        if (!$this->getRequest()->isAjax()) {
            throw new NotFoundException(__('Request type is incorrect'));
        }
    }

    /**
     * @param $value
     * @param int $duration
     * @return $this
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Stdlib\Cookie\CookieSizeLimitReachedException
     * @throws \Magento\Framework\Stdlib\Cookie\FailureToSendException
     */
    public function setCookie($name, $value, $duration = 1440){
        $metadata = $this->cookieMetadataFactory
            ->createPublicCookieMetadata()
            ->setDuration($duration)
            ->setPath($this->sessionManager->getCookiePath())
            ->setDomain($this->sessionManager->getCookieDomain());

        $this->cookieManager->setPublicCookie(
            $name,
            $value,
            $metadata);
        return $this;
    }
}
