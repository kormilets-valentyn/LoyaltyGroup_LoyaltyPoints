<?php

use Magento\Framework\Component\ComponentRegistrar;

$registrar = new ComponentRegistrar();
if ($registrar->getPath(ComponentRegistrar::MODULE, 'LoyaltyGroup_LoyaltyPoints') === null) {
    ComponentRegistrar::register(ComponentRegistrar::MODULE, 'LoyaltyGroup_LoyaltyPoints', __DIR__);
}
