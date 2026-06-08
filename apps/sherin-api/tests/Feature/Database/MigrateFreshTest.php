<?php

namespace Tests\Feature\Database;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class MigrateFreshTest extends TestCase
{
    use RefreshDatabase;

    public static function v1TableProvider(): array
    {
        return [
            ['activities'],
            ['stores'],
            ['categories'],
            ['products'],
            ['customers'],
            ['invoices'],
            ['invoice_products'],
            ['campaigns'],
            ['campaign_details'],
            ['rdvs'],
            ['messages_customers'],
        ];
    }

    #[DataProvider('v1TableProvider')]
    public function test_v1_ported_tables_exist(string $table): void
    {
        $this->assertTrue(Schema::hasTable($table), "Missing table: {$table}");
    }
}
