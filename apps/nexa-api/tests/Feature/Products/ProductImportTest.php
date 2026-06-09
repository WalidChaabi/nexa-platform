<?php

namespace Tests\Feature\Products;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class ProductImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_import_flat_spreadsheet(): void
    {
        $owner = User::factory()->owner()->create();
        $store = Store::factory()->create(['user_id' => $owner->id]);
        $file = $this->makeSpreadsheet([
            ['Label', 'Price', 'Promo', 'Qty'],
            ['Shampoo', 120, 100, 5],
            ['Conditioner', 80, 80, 10],
        ]);

        $response = $this->actingAs($owner)
            ->withHeaders(['Accept' => 'application/json'])
            ->post('/api/v1/products/import', [
                'store_id' => $store->id,
                'file' => $file,
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('created', 2)
            ->assertJsonPath('categories', 0)
            ->assertJsonCount(0, 'errors');

        $this->assertDatabaseHas('products', [
            'store_id' => $store->id,
            'label' => 'Shampoo',
            'price' => 120,
            'promotion_price' => 100,
            'quantite' => 5,
        ]);
    }

    public function test_owner_can_import_categorized_spreadsheet(): void
    {
        $owner = User::factory()->owner()->create();
        $store = Store::factory()->create(['user_id' => $owner->id]);
        $file = $this->makeSpreadsheet([
            ['Label', 'Price', 'Promo', 'Qty', 'Extra', 'Category'],
            ['Shampoo', 120, 100, 5, '', 'Hair'],
            ['Conditioner', 80, 80, 10, '', 'Hair'],
            ['Nail polish', 45, 45, 3, '', 'Nails'],
        ]);

        $response = $this->actingAs($owner)
            ->withHeaders(['Accept' => 'application/json'])
            ->post('/api/v1/products/import', [
                'store_id' => $store->id,
                'file' => $file,
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('created', 3)
            ->assertJsonPath('categories', 2)
            ->assertJsonCount(0, 'errors');

        $hairCategory = Category::query()
            ->where('store_id', $store->id)
            ->where('name', 'Hair')
            ->first();

        $this->assertNotNull($hairCategory);
        $this->assertDatabaseHas('products', [
            'store_id' => $store->id,
            'categorie_id' => $hairCategory->id,
            'label' => 'Conditioner',
        ]);
    }

    public function test_import_returns_row_errors_without_failing_valid_rows(): void
    {
        $owner = User::factory()->owner()->create();
        $store = Store::factory()->create(['user_id' => $owner->id]);
        $file = $this->makeSpreadsheet([
            ['Label', 'Price', 'Promo', 'Qty'],
            ['Valid product', 50, 50, 2],
            ['', 30, 30, 1],
            ['Bad price', 'abc', 30, 1],
        ]);

        $response = $this->actingAs($owner)
            ->withHeaders(['Accept' => 'application/json'])
            ->post('/api/v1/products/import', [
                'store_id' => $store->id,
                'file' => $file,
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('created', 1)
            ->assertJsonCount(2, 'errors');

        $this->assertSame(1, Product::query()->where('store_id', $store->id)->count());
    }

    public function test_employer_cannot_import_for_other_store(): void
    {
        $store = Store::factory()->create();
        $otherStore = Store::factory()->create();
        $employer = User::factory()->employer()->create(['store_id' => $store->id]);
        $file = $this->makeSpreadsheet([
            ['Label', 'Price', 'Promo', 'Qty'],
            ['Shampoo', 120, 100, 5],
        ]);

        $this->actingAs($employer)
            ->withHeaders(['Accept' => 'application/json'])
            ->post('/api/v1/products/import', [
                'store_id' => $otherStore->id,
                'file' => $file,
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['store_id']);
    }

    /**
     * @param  list<list<mixed>>  $rows
     */
    private function makeSpreadsheet(array $rows): UploadedFile
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($rows);

        $path = tempnam(sys_get_temp_dir(), 'product-import');
        (new Xlsx($spreadsheet))->save($path);

        return new UploadedFile(
            $path,
            'products.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true,
        );
    }
}
