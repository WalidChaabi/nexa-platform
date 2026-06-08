<?php

namespace Tests\Feature\Security;

use PHPUnit\Framework\TestCase;

class NoGetDeleteRoutesTest extends TestCase
{
    public function test_web_routes_do_not_expose_get_delete_endpoints()
    {
        $contents = file_get_contents(dirname(__DIR__, 3) . '/routes/web.php');

        $this->assertNotRegExp(
            "/->get\\([^)]*\\/delete/",
            $contents,
            'Destructive actions must not use GET routes containing /delete'
        );

        $this->assertNotRegExp(
            "/->get\\([^)]*delete-rdv/",
            $contents,
            'RDV deletion must not use GET delete-rdv route'
        );
    }

    public function test_destructive_admin_routes_use_delete_method()
    {
        $contents = file_get_contents(dirname(__DIR__, 3) . '/routes/web.php');

        $this->assertContains("->delete('owners/{id}'", $contents);
        $this->assertContains("->delete('/stores/{id}'", $contents);
        $this->assertContains("->delete('cross-products/{id}'", $contents);
        $this->assertContains("->delete('employees/{id}'", $contents);
        $this->assertContains("->delete('/rdv/{id}'", $contents);
    }
}
