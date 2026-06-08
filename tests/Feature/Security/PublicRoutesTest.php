<?php

namespace Tests\Feature\Security;

use Tests\TestCase;

class PublicRoutesTest extends TestCase
{
    /** @var array */
    protected $removedRoutes = [
        '/clear',
        '/cross-selling',
        '/rendez-vous',
        '/unique',
        '/bienvenu',
        '/rdv',
        '/rdv/update',
    ];

    /**
     * @dataProvider removedRoutesProvider
     */
    public function test_removed_debug_routes_are_not_publicly_accessible($path)
    {
        $response = $this->get($path);

        $this->assertTrue(
            in_array($response->getStatusCode(), [404, 403, 302, 401], true),
            "Route {$path} should not return 200 OK (got {$response->getStatusCode()})"
        );
    }

    public function removedRoutesProvider()
    {
        foreach ($this->removedRoutes as $path) {
            yield $path => [$path];
        }
    }
}
