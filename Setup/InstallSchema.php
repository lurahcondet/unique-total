<?php

/**
 * Xtendable_UniqueTotal
 *
 * @see README.md
 *
 */

declare(strict_types=1);

namespace Xtendable\UniqueTotal\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
 
/**
 * class InstallSchema
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * Upgrades DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        // Add fee and baseUnique
        foreach (['quote', 'quote_address', 'sales_order', 'sales_invoice', 'sales_creditmemo'] as $table) {
            $this->addColumn($setup, $table, 'unique_amount', 'Unique Amount');
            $this->addColumn($setup, $table, 'base_unique_amount', 'Base Unique Amount');
        }

        // Add uniqueInvoiced, baseUniqueInvoiced, uniqueRefunded, baseUniqueRefunded
        $this->addColumn($setup, 'sales_order', 'unique_amount_invoiced', 'Unique Amount Invoiced');
        $this->addColumn($setup, 'sales_order', 'base_unique_amount_invoiced', 'Base Unique Amount Invoiced');
        $this->addColumn($setup, 'sales_order', 'unique_amount_refunded', 'Unique Amount Refunded');
        $this->addColumn($setup, 'sales_order', 'base_unique_amount_refunded', 'Base Unique Amount Refunded');

        $setup->endSetup();
    }

    /**
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param string $table
     * @param string $name
     * @param string $description
     */
    public function addColumn(SchemaSetupInterface $setup, $table, $name, $description)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable($table),
            $name,
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                'length' => '12,4',
                'default' => 0.0000,
                'nullable' => true,
                'comment' => $description
            ]
        );
    }
}
