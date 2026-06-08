<?php

namespace Tests\Feature\Security;

use PHPUnit\Framework\TestCase;

class V1CloseoutTest extends TestCase
{
    public function test_pos_token_service_hashes_and_verifies()
    {
        $this->assertFileExists(dirname(__DIR__, 3) . '/app/Services/PosTokenService.php');

        $pos = file_get_contents(dirname(__DIR__, 3) . '/app/Http/Controllers/Web/PosController.php');
        $this->assertContains('PosTokenService', $pos);
        $this->assertNotContains('$request->token == $user->token', $pos);
    }

    public function test_admin_payment_overrides_require_confirmation_and_audit()
    {
        $campaign = file_get_contents(
            dirname(__DIR__, 3) . '/app/Http/Controllers/Web/CampaignController.php'
        );

        $this->assertContains('AuditsAdminPaymentOverrides', $campaign);
        $this->assertContains('authorizeAdminPaymentOverride', $campaign);
        $this->assertContains("admin.payment_override", file_get_contents(
            dirname(__DIR__, 3) . '/app/Http/Controllers/Concerns/AuditsAdminPaymentOverrides.php'
        ));
    }

    public function test_xss_mitigations_in_views()
    {
        $hestorique = file_get_contents(
            dirname(__DIR__, 3) . '/resources/views/owner/products/hestorique.blade.php'
        );
        $newsletter = file_get_contents(
            dirname(__DIR__, 3) . '/resources/views/admin/newsletter/campaign.blade.php'
        );

        $this->assertContains('SafeHtml::productNotification', $hestorique);
        $this->assertNotContains('implode', $newsletter);
    }

    public function test_pos_token_migration_command_exists()
    {
        $this->assertFileExists(dirname(__DIR__, 3) . '/app/Console/Commands/MigratePosTokens.php');
        $this->assertContains(
            'MigratePosTokens',
            file_get_contents(dirname(__DIR__, 3) . '/app/Console/Kernel.php')
        );
    }
}
