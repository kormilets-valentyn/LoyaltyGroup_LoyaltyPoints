<?php

namespace LoyaltyGroup\LoyaltyPoints\Model\ResourceModel\CustomerPoints;

use LoyaltyGroup\LoyaltyPoints\Model\CustomerPoints as Model;
use LoyaltyGroup\LoyaltyPoints\Model\ResourceModel\CustomerPoints as ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    public function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
