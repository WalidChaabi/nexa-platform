<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProductImportService
{
    /**
     * @return array{created: int, categories: int, errors: list<array{row: int, message: string}>}
     */
    public function import(int $storeId, string $filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, false);

        $rows = array_values(array_filter(
            $rows,
            fn (array $row): bool => $this->rowHasData($row),
        ));

        if ($rows === []) {
            return [
                'created' => 0,
                'categories' => 0,
                'errors' => [
                    ['row' => 1, 'message' => 'The spreadsheet is empty.'],
                ],
            ];
        }

        unset($rows[0]);
        $rows = array_values($rows);

        if ($rows === []) {
            return [
                'created' => 0,
                'categories' => 0,
                'errors' => [
                    ['row' => 2, 'message' => 'No product rows found after the header.'],
                ],
            ];
        }

        $columnCount = count($rows[0]);

        if ($columnCount === 6) {
            return $this->importWithCategories($storeId, $rows);
        }

        if ($columnCount === 4) {
            return $this->importFlat($storeId, $rows);
        }

        return [
            'created' => 0,
            'categories' => 0,
            'errors' => [
                [
                    'row' => 2,
                    'message' => 'Expected 4 or 6 columns (label, price, promo, qty[, extra, category]).',
                ],
            ],
        ];
    }

    /**
     * @param  list<array<int, mixed>>  $rows
     * @return array{created: int, categories: int, errors: list<array{row: int, message: string}>}
     */
    private function importWithCategories(int $storeId, array $rows): array
    {
        $created = 0;
        $categoriesCreated = 0;
        $errors = [];
        $grouped = [];

        foreach ($rows as $index => $row) {
            $excelRow = $index + 2;
            $parsed = $this->parseRow($row, $excelRow);

            if (isset($parsed['error'])) {
                $errors[] = $parsed['error'];

                continue;
            }

            $categoryName = trim((string) ($row[5] ?? ''));

            if ($categoryName === '') {
                $errors[] = [
                    'row' => $excelRow,
                    'message' => 'Category name is required in column 6.',
                ];

                continue;
            }

            $grouped[$categoryName][] = $parsed['data'];
        }

        foreach ($grouped as $categoryName => $products) {
            $category = Category::query()->create([
                'store_id' => $storeId,
                'name' => $categoryName,
                'description' => '',
            ]);
            $categoriesCreated++;

            foreach ($products as $productData) {
                Product::query()->create([
                    'store_id' => $storeId,
                    'categorie_id' => $category->id,
                    ...$productData,
                ]);
                $created++;
            }
        }

        return [
            'created' => $created,
            'categories' => $categoriesCreated,
            'errors' => $errors,
        ];
    }

    /**
     * @param  list<array<int, mixed>>  $rows
     * @return array{created: int, categories: int, errors: list<array{row: int, message: string}>}
     */
    private function importFlat(int $storeId, array $rows): array
    {
        $created = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            $excelRow = $index + 2;
            $parsed = $this->parseRow($row, $excelRow);

            if (isset($parsed['error'])) {
                $errors[] = $parsed['error'];

                continue;
            }

            Product::query()->create([
                'store_id' => $storeId,
                ...$parsed['data'],
            ]);
            $created++;
        }

        return [
            'created' => $created,
            'categories' => 0,
            'errors' => $errors,
        ];
    }

    /**
     * @param  array<int, mixed>  $row
     * @return array{data?: array{label: string, price: float, promotion_price: float, quantite: int}, error?: array{row: int, message: string}}
     */
    private function parseRow(array $row, int $excelRow): array
    {
        $label = trim((string) ($row[0] ?? ''));

        if ($label === '') {
            return [
                'error' => [
                    'row' => $excelRow,
                    'message' => 'Product label is required.',
                ],
            ];
        }

        if (! is_numeric($row[1] ?? null)) {
            return [
                'error' => [
                    'row' => $excelRow,
                    'message' => 'Price must be a number.',
                ],
            ];
        }

        $price = (float) $row[1];
        $promotionRaw = $row[2] ?? null;
        $promotionPrice = $promotionRaw === null || $promotionRaw === ''
            ? $price
            : (is_numeric($promotionRaw) ? (float) $promotionRaw : null);

        if ($promotionPrice === null) {
            return [
                'error' => [
                    'row' => $excelRow,
                    'message' => 'Promotion price must be a number.',
                ],
            ];
        }

        $quantityRaw = $row[3] ?? 0;
        $quantite = is_numeric($quantityRaw) ? (int) $quantityRaw : null;

        if ($quantite === null || $quantite < 0) {
            return [
                'error' => [
                    'row' => $excelRow,
                    'message' => 'Quantity must be a non-negative number.',
                ],
            ];
        }

        return [
            'data' => [
                'label' => $label,
                'price' => $price,
                'promotion_price' => $promotionPrice,
                'quantite' => $quantite,
            ],
        ];
    }

    /**
     * @param  array<int, mixed>  $row
     */
    private function rowHasData(array $row): bool
    {
        foreach ($row as $value) {
            if ($value !== null && $value !== '') {
                return true;
            }
        }

        return false;
    }
}
