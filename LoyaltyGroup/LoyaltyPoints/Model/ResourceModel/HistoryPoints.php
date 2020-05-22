<?php

namespace LoyaltyGroup\LoyaltyPoints\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class HistoryPoints extends AbstractDb
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('loyalty_points_history', 'id');
    }
}
