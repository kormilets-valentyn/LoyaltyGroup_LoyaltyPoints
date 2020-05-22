<?php

namespace LoyaltyGroup\LoyaltyPoints\Model;

use LoyaltyGroup\LoyaltyPoints\Api\UserInterface;
use Magento\Framework\Model\AbstractModel;
use LoyaltyGroup\LoyaltyPoints\Model\ResourceModel\CustomerPoints as ResourceModel;

class CustomerPoints extends AbstractModel implements UserInterface
{
    public function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**post
     * @return mixed
     */
    public function getPoints()
    {
        return $this->getData('points');
    }

    /**
     * @param $points
     * @return CustomerPoints
     */
    public function setPoints($points)
    {
        $this->setData('points', $points);
        return $this;
    }
}
