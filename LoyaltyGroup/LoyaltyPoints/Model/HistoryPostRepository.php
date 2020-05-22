<?php

namespace LoyaltyGroup\LoyaltyPoints\Model;

use LoyaltyGroup\LoyaltyPoints\Model\ResourceModel\HistoryPoints as ResourceModel;
use Magento\Framework\Exception\AlreadyExistsException;

class HistoryPostRepository
{
    protected $resource;
    protected $_model;

    /**
     * HistoryPostRepository constructor.
     * @param ResourceModel $resource
     * @param CustomerPointsFactory $model
     */
    public function __construct(
        ResourceModel $resource,
        CustomerPointsFactory $model
    ) {
        $this->resource = $resource;
        $this->_model = $model;
    }

    /**
     * @return CustomerPoints
     */
    public function add()
    {
        return $this->_model->create();
    }

    /**
     * @param $points
     */
    public function save($points)
    {
        try {
            $this->resource->save($points);
        } catch (AlreadyExistsException $e) {
        } catch (\Exception $e) {
        }
    }

}
