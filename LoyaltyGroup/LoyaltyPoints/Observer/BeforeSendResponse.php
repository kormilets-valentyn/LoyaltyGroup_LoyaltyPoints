<?php

namespace LoyaltyGroup\LoyaltyPoints\Observer;

use LoyaltyGroup\LoyaltyPoints\Block\Post;
use Magento\Customer\Model\Session;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class BeforeSendResponse implements ObserverInterface
{
    protected $session;
    protected $block;

    /**
     * BeforeSendResponse constructor.
     * @param Session $session
     * @param Post $block
     */
    public function __construct(Session $session, Post $block)
    {
        $this->session = $session;
        $this->block = $block;
    }

    /**
     * @param Observer $observer
     * @throws \Magento\Framework\Exception\SessionException
     */
    public function execute(Observer $observer)
    {
        $data = $observer->getEvent()->getData('request')->getParam('ref');
//        var_dump($this->session->getReferenceId());
//        var_dump($this->session->getUsePoints());
        if ($this->session->getReferenceId() == null && $data != $this->block->getCustomerId()) {
            $this->session->setReferenceId($data);
        }
    }
}
