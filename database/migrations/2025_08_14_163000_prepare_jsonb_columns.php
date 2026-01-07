<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Columns that need explicit jsonb casting before Lunar's migration.
     *
     * @var array<string, array<int, string>>
     */
    private array $columnsToCast = [
        'activity_log' => [
            'properties',
        ],
        'addresses' => [
            'meta',
        ],
        'attribute_groups' => [
            'name',
        ],
        'attributes' => [
            'configuration',
            'description',
            'name',
        ],
        'brands' => [
            'attribute_data',
        ],
        'cart_addresses' => [
            'meta',
        ],
        'cart_lines' => [
            'meta',
        ],
        'carts' => [
            'meta',
        ],
        'collections' => [
            'attribute_data',
        ],
        'customer_groups' => [
            'attribute_data',
        ],
        'customers' => [
            'attribute_data',
            'meta',
        ],
        'discounts' => [
            'data',
        ],
        'order_addresses' => [
            'meta',
        ],
        'order_lines' => [
            'meta',
            'tax_breakdown',
        ],
        'orders' => [
            'discount_breakdown',
            'meta',
            'shipping_breakdown',
            'tax_breakdown',
        ],
        'product_option_values' => [
            'name',
        ],
        'product_options' => [
            'label',
            'name',
        ],
        'product_variants' => [
            'attribute_data',
        ],
        'products' => [
            'attribute_data',
        ],
        'transactions' => [
            'meta',
        ],
        'media' => [
            'custom_properties',
            'generated_conversions',
            'manipulations',
            'responsive_images',
        ],
    ];

    public function up(): void
    {
        $connection = config('lunar.database.connection');
        $db = DB::connection($connection);
        $schema = Schema::connection($connection);

        if ($db->getDriverName() !== 'pgsql') {
            return;
        }

        foreach ($this->columnsToCast as $table => $columns) {
            $fullTable = $this->fullTableName($table);

            if (! $schema->hasTable($fullTable)) {
                continue;
            }

            foreach ($columns as $column) {
                if (! $schema->hasColumn($fullTable, $column)) {
                    continue;
                }

                $db->statement(sprintf(
                    'ALTER TABLE "%s" ALTER COLUMN "%s" TYPE jsonb USING "%s"::jsonb',
                    $fullTable,
                    $column,
                    $column
                ));
            }
        }
    }

    public function down(): void
    {
        // No-op: Lunar's migration handles reversing jsonb changes.
    }

    private function fullTableName(string $table): string
    {
        if (in_array($table, ['activity_log', 'media'], true)) {
            return $table;
        }

        $prefix = config('lunar.database.table_prefix', '');

        return $prefix.$table;
    }
};
