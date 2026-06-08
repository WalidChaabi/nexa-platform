<?php

namespace Tests\Feature\Security;

use PHPUnit\Framework\TestCase;

class MassAssignmentTest extends TestCase
{
    public function test_models_do_not_mass_assign_primary_keys()
    {
        $root = dirname(__DIR__, 3) . '/app/Models';
        $models = [
            'Campaign.php',
            'Code.php',
            'Filter.php',
            'Message.php',
            'ProductQuantite.php',
            'ProductHestorique.php',
            'MessageCampaign.php',
            'MessageCustomer.php',
        ];

        foreach ($models as $model) {
            $contents = file_get_contents($root . '/' . $model);
            $this->assertNotRegExp(
                "/'id'\\s*,/",
                $contents,
                "Expected {$model} fillable to exclude id"
            );
        }
    }

    public function test_user_and_customer_models_exclude_privileged_fields()
    {
        $appUser = file_get_contents(dirname(__DIR__, 3) . '/app/User.php');
        $modelUser = file_get_contents(dirname(__DIR__, 3) . '/app/Models/User.php');
        $customer = file_get_contents(dirname(__DIR__, 3) . '/app/Models/Customer.php');

        foreach ([$appUser, $modelUser] as $userModel) {
            $this->assertNotContains("'is_admin'", $userModel);
            $this->assertNotContains("'token'", $userModel);
        }

        $this->assertNotContains("'points'", $customer);
        $this->assertNotRegExp("/'id'\\s*,/", $customer);
    }

    public function test_invoice_model_does_not_mass_assign_points()
    {
        $invoice = file_get_contents(dirname(__DIR__, 3) . '/app/Models/Invoice.php');
        $this->assertNotContains("'points'", $invoice);
    }

    public function test_write_controllers_avoid_request_all_on_persist()
    {
        $root = dirname(__DIR__, 3) . '/app/Http/Controllers/Web';
        $controllers = [
            'ProductController.php',
            'CampaignDetailController.php',
            'CategorieController.php',
            'EpinglesController.php',
            'TableController.php',
            'CrossProductController.php',
            'InvoiceController.php',
        ];

        foreach ($controllers as $controller) {
            $contents = file_get_contents($root . '/' . $controller);
            $this->assertNotContains('::create($request->all())', $contents, $controller);
            $this->assertNotContains('->update($request->all())', $contents, $controller);
        }

        $invoice = file_get_contents($root . '/InvoiceController.php');
        $this->assertContains('validatedInvoicePayload', $invoice);
    }
}
