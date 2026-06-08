<?php

namespace Tests\Feature\Security;

use PHPUnit\Framework\TestCase;

class BulkSmsConfigTest extends TestCase
{
    public function test_bulksms_token_is_not_hardcoded_in_services()
    {
        $paths = [
            base_path('app/Services/CampaignService.php'),
            base_path('app/Services/UniqueService.php'),
            base_path('app/Services/RendezVousService.php'),
            base_path('app/Services/SmsService.php'),
            base_path('app/Services/BulkSmsClient.php'),
        ];

        foreach ($paths as $path) {
            $contents = file_get_contents($path);
            $this->assertNotContains('a4B2EZpCjP72JwA33JRTwYQ1e', $contents, "Hardcoded token found in {$path}");
            $this->assertNotContains("'token=a4B2EZ", $contents, "Hardcoded query string token found in {$path}");
        }
    }

    public function test_services_config_defines_bulksms_block()
    {
        $contents = file_get_contents(base_path('config/services.php'));

        $this->assertContains("'bulksms'", $contents);
        $this->assertContains('BULKSMS_TOKEN', $contents);
    }
}
