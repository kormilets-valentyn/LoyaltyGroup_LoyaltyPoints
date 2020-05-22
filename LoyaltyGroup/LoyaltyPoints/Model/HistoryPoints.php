<?php

namespace LoyaltyGroup\LoyaltyPoints\Model;

use LoyaltyGroup\LoyaltyPoints\Model\ResourceModel\HistoryPoints as ResourceModel;
use Magento\Framework\Model\AbstractModel;

class HistoryPoints extends AbstractModel
{
    public function _construct()
    {
        $this->_init(ResourceModel::class);
    }
}
