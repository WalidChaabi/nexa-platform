<?php

namespace Tests\Feature\Security;

use PHPUnit\Framework\TestCase;

class SecureUploadTest extends TestCase
{
    public function test_store_and_product_controllers_do_not_use_client_original_name_for_uploads()
    {
        $root = dirname(__DIR__, 3);
        $paths = [
            $root . '/app/Http/Controllers/Web/StoreController.php',
            $root . '/app/Http/Controllers/Web/ProductController.php',
        ];

        foreach ($paths as $path) {
            $contents = file_get_contents($path);
            $this->assertNotContains('getClientOriginalName()', $contents, "Unsafe filename usage in {$path}");
            $this->assertContains('SecureImageUpload', $contents, "SecureImageUpload expected in {$path}");
        }
    }

    public function test_secure_image_upload_helper_exists()
    {
        $this->assertFileExists(dirname(__DIR__, 3) . '/app/Support/SecureImageUpload.php');
    }
}
