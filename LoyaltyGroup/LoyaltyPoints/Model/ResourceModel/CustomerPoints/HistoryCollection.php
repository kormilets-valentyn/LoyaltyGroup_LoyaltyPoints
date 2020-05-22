<?php

namespace LoyaltyGroup\LoyaltyPoints\Model\ResourceModel\CustomerPoints;

use LoyaltyGroup\LoyaltyPoints\Model\HistoryPoints as Model;
use LoyaltyGroup\LoyaltyPoints\Model\ResourceModel\HistoryPoints as ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class HistoryCollection extends AbstractCollection
{
    public function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}