<?php

namespace LoyaltyGroup\LoyaltyPoints\Block;

use LoyaltyGroup\LoyaltyPoints\Model\HistoryPostRepository;
use LoyaltyGroup\LoyaltyPoints\Model\PostRepository;
use LoyaltyGroup\LoyaltyPoints\Model\ResourceModel\CustomerPoints\HistoryCollectionFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\StoreManagerInterface;

class Post extends Template
{
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;
    protected $customerSession;
    protected $repository;
    protected $_storeManager;
    protected $historyRepo;
    protected $collectionFactory;

    const XML_PATH_COUNT_LOYALTY = 'loyalty/general/display_text';

    /**
     * Post constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param Session $customerSession
     * @param Context $context
     * @param PostRepository $repository
     * @param StoreManagerInterface $storeManager
     * @param HistoryPostRepository $historyRepo
     * @param HistoryCollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Session $customerSession,
        Context $context,
        PostRepository $repository,
        StoreManagerInterface $storeManager,
        HistoryPostRepository $historyRepo,
        HistoryCollectionFactory $collectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->repository = $repository;
        $this->customerSession = $customerSession;
        $this->scopeConfig = $scopeConfig;
        $this->_storeManager = $storeManager;
        $this->historyRepo = $historyRepo;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Sample function returning config value
     **/

    public function customConfig()
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->getValue(self::XML_PATH_COUNT_LOYALTY, $storeScope);
    }

    /**
     * @return mixed
     */
    public function getCustomerId()
    {
        return $this->customerSession->getCustomer()->getId();
    }

    /**
     * @return mixed
     */
    public function getPointsCount()
    {
        $userId = $this->getCustomerId();
        if (!empty($userId)) {
            try {
                return $this->repository->getById($userId)->getData('points');
            } catch (NoSuchEntityException $e) {
                return $e;
            }
        } else {
            return 0;
        }
    }

    /**
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getRefLink()
    {
        return $this->_storeManager->getStore()->getBaseUrl();
    }

    /**
     * @return \LoyaltyGroup\LoyaltyPoints\Api\UserInterface
     * @throws NoSuchEntityException
     */
    public function getUser()
    {
        $id = $this->getCustomerId();
        return $this->repository->getById($id);
    }

    /**
     * Get history of cancel and accrued points of current user
     * @return array
     */
    public function getHistory()
    {
        $collection = $this->collectionFactory->create()->getData();
        $result = [];
        foreach ($collection as $value) {
            if ($value['user_id'] == $this->getCustomerId()) {
                $result[] = $value;
            }
        }
        return $result;
    }
}
