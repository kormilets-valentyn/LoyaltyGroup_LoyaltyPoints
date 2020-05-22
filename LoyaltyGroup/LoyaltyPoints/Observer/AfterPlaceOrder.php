<?php

namespace LoyaltyGroup\LoyaltyPoints\Observer;

use LoyaltyGroup\LoyaltyPoints\Block\Post;
use LoyaltyGroup\LoyaltyPoints\Controller\Customer\Customer as myCookie;
use LoyaltyGroup\LoyaltyPoints\Model\HistoryPostRepository as History;
use LoyaltyGroup\LoyaltyPoints\Model\PostRepository;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;

class AfterPlaceOrder implements ObserverInterface
{
    protected $scopeConfig;
    protected $repository;
    protected $session;
    protected $getUserId;
    protected $history;
    protected $timestamp;
    protected $cookie;

    /**
     * AfterPlaceOrder constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param PostRepository $repository
     * @param Session $session
     * @param Post $getUserId
     * @param History $history
     * @param DateTime $timestamp
     * @param myCookie $cookie
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        PostRepository $repository,
        Session $session,
        Post $getUserId,
        History $history,
        DateTime $timestamp,
        myCookie $cookie
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->repository = $repository;
        $this->session = $session;
        $this->getUserId = $getUserId;
        $this->history = $history;
        $this->timestamp = $timestamp;
        $this->cookie = $cookie;
    }

    /**
     * @param Observer $observer
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(Observer $observer)
    {
        $item = $observer->getEvent()->getOrder()->getBaseTotalDue();
        $percent = $this->getUserId->customConfig();
        $point = round($item * $percent / 100);
        $id = $this->session->getReferenceId();
        if (!empty($id) && $this->getUserId->getCustomerId() != $id) {
            $datas = $this->repository->getById($id)->getPoints();
            $totalPoints = $datas + $point;
            $user = $this->repository->getById($id)->setPoints($totalPoints);
            $this->repository->save($user);

            $historyPoints = $this->history->add();
            $historyPoints->addData([
                "points_count" => $point,
                "status" => 'accrued',
                "timestamp" => $this->timestamp->gmtDate(),
                "user_id" => $id
            ]);
            $this->history->save($historyPoints);
        }
        $cancelPoints = round($this->session->getCancelOfPoints());
        if (!empty($cancelPoints)) {
            $userId = $this->getUserId->getCustomerId();
            $userPoints = $this->repository->getById($userId)->getPoints();
            $cancel = $userPoints - $cancelPoints;
            $updateUser = $this->repository->getById($userId);
            $updateUser->setPoints($cancel);
            $this->repository->save($updateUser);
            /**
             * set information to history
             */
            $historyPoints = $this->history->add();
            $historyPoints->addData([
                    "points_count" => $cancelPoints,
                    "status" => 'cancel',
                    "timestamp" => $this->timestamp->gmtDate(),
                    "user_id" => $userId
                ]);
            $this->history->save($historyPoints);
            /**
             * clear all value from session and cookies
             */
            $this->session->unsUsePoints();
            $this->session->unsCancelOfPoints();
            $this->cookie->setCookie('usePointsOrNot', 0);
            $this->cookie->setCookie('checkoutPosition', false);
        }
    }
}
