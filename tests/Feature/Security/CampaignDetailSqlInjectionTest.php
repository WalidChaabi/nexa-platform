<?php

namespace Tests\Feature\Security;

use PHPUnit\Framework\TestCase;

class CampaignDetailSqlInjectionTest extends TestCase
{
    public function test_campaign_detail_controller_does_not_concatenate_store_id_into_raw_sql()
    {
        $contents = file_get_contents(
            dirname(__DIR__, 3) . '/app/Http/Controllers/Web/CampaignDetailController.php'
        );

        $this->assertNotRegExp(
            '/DB::raw\([^)]*\$request->store_id/',
            $contents,
            'User input must not be concatenated into DB::raw()'
        );

        $this->assertContains('Customer::where(\'store_id\', $storeId)', $contents);
    }
}
