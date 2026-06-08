<?php

namespace Tests\Feature\Security;

use App\Services\ProductService;
use PHPUnit\Framework\TestCase;

class ProductMessageXssTest extends TestCase
{
    public function test_product_history_message_escapes_user_supplied_values()
    {
        $service = new ProductService();
        $message = $service->Message('créer', [
            'userName' => '<script>alert(1)</script>',
            'productName' => '<img onerror=alert(1)>',
            'date' => '2026-01-01',
        ], null);

        $this->assertNotContains('<script>', $message);
        $this->assertContains('&lt;script&gt;alert(1)&lt;/script&gt;', $message);
        $this->assertContains('&lt;img onerror=alert(1)&gt;', $message);
    }
}
