<?php

namespace Tests\Feature\Auth;

use PHPUnit\Framework\TestCase;

class LoginThrottleTest extends TestCase
{
    public function test_login_route_uses_throttle_middleware()
    {
        $routes = file_get_contents(dirname(__DIR__, 3) . '/routes/web.php');

        $this->assertContains("Route::post('/login'", $routes);
        $this->assertContains('throttle:5,1', $routes);
    }
}
