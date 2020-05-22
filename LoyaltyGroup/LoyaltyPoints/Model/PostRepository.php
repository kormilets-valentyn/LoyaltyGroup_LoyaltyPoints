<?php

namespace LoyaltyGroup\LoyaltyPoints\Model;

use LoyaltyGroup\LoyaltyPoints\Api\UserRepositoryInterface;
use LoyaltyGroup\LoyaltyPoints\Model\ResourceModel\CustomerPoints as ResourceModel;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\App\ResourceConnection;

class PostRepository implements UserRepositoryInterface
{
    protected $resource;
    protected $connection;
    protected $_model;

    /**
     * PostRepository constructor.
     * @param ResourceModel $resource
     * @param ResourceConnection $resourceConnection
     * @param CustomerPointsFactory $model
     */
    public function __construct(
        ResourceModel $resource,
        ResourceConnection $resourceConnection,
        CustomerPointsFactory $model
    ) {
        $this->resource = $resource;
        $this->connection = $resourceConnection;
        $this->_model = $model;
    }

    /**
     * @param int $id
     * @return \LoyaltyGroup\LoyaltyPoints\Api\UserInterface|CustomerPoints
     * @throws NoSuchEntityException
     */
    public function getById(int $id)
    {
        $post = $this->_model->create();
        $this->resource->load($post, $id);
        if (!$post->getId()) {
            throw new NoSuchEntityException(__('Post with id "%1" does not exist.', $id));
        }
        return $post;
    }

    /**
     * @param $user
     */
    public function save($user)
    {
        try {
            $this->resource->save($user);
        } catch (AlreadyExistsException $e) {
        } catch (\Exception $e) {
        }
    }
}
