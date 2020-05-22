<?php

namespace LoyaltyGroup\LoyaltyPoints\Api;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use LoyaltyGroup\LoyaltyPoints\Api\UserInterface;

interface UserRepositoryInterface
{
    /**
     * Get user by ID.
     *
     * @param int $id
     * @throws NoSuchEntityException
     * @return UserInterface
     */
    public function getById(int $id);
}
