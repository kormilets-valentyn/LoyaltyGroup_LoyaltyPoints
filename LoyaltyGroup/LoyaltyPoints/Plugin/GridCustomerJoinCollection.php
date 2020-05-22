<?php

namespace LoyaltyGroup\LoyaltyPoints\Plugin;

use Magento\Framework\Data\Collection;

class GridCustomerJoinCollection
{
    public static $table = 'customer_grid_flat';
    public static $leftJoinTable = 'customer_entity'; // My custom table
    /**
     * Get report collection
     *
     * @param string $requestName
     * @return Collection
     * @throws \Exception
     */
    public function afterGetReport(
        \Magento\Framework\View\Element\UiComponent\DataProvider\CollectionFactory $subject,
        $collection,
        $requestName
    ) {
        if ($requestName == 'customer_listing_data_source') {
            if ($collection->getMainTable() === $collection->getConnection()->getTableName(self::$table)) {
                $leftJoinTableName = $collection->getConnection()->getTableName(self::$leftJoinTable);

                $collection
                    ->getSelect()
                    ->joinLeft(
                        ['co'=>$leftJoinTableName],
                        "co.entity_id = main_table.entity_id",
                        [
                            'entity_id' => 'co.entity_id',
                            'points'=> 'co.points'
                        ]
                    );
                /* return data with left join customer_id from sales_order and points*/

                $where = $collection->getSelect()->getPart(\Magento\Framework\DB\Select::WHERE);

                $collection->getSelect()->setPart(\Magento\Framework\DB\Select::WHERE, $where)->group('main_table.entity_id');
            }
        }
        return $collection;
    }
}
