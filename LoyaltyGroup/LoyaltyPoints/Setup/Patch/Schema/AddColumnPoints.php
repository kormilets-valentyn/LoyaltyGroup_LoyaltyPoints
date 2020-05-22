<?php

namespace LoyaltyGroup\LoyaltyPoints\Setup\Patch\Schema;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class AddColumnPoints implements SchemaPatchInterface
{
    /**
     * @var SchemaSetupInterface
     */
    private $setup;

    public function __construct(SchemaSetupInterface $setup)
    {
        $this->setup = $setup;
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function apply()
    {
        $this->setup->startSetup();
        $this->setup
             ->getConnection()
             ->addColumn(
                 $this->setup->getTable('customer_entity'),
                 'points',
                 [
                     'type'=> Table::TYPE_INTEGER,
                     'nullable'=>true,
                     'unsigned' => true,
                     'default' => 0,
                     'comment'=> 'points'
                 ]
             );
        $this->setup->endSetup();
    }
}
