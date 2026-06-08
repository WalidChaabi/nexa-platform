<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\ProductQuantiteDetails;

class ProductsDetails2 implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $storeId;

    public function __construct($storeId)
    {
        $this->storeId = $storeId;
    }

    public function collection()
    {
        $products = ProductQuantiteDetails::join('products', 'product_quantite_details.product_id', '=', 'products.id')
            ->join('product_quantites', 'product_quantite_details.product_quantite_id', '=', 'product_quantites.id')
            ->where('product_quantite_details.store_id', $this->storeId)
            ->select('products.label', 'products.id', 'product_quantites.quantite', 'product_quantites.created_at')
            ->get();
        $result = collect();
        $data = collect();

        foreach ($products as $product) {
            $label = $product->label;

            if (!$result->has($label)) {
                $result->put($label, collect());
            }

            $result->get($label)->push([
                'quantite' => $product->quantite,
                'created_at' => $product->created_at,
            ]);
        }

        $data->push($result);
        return $data;
    }
    public function headings(): array
    {
        $headings = [
            trans('Produits'),
        ];
        $total = [
            trans('Total'),
        ];

        $productQuantites = $this->collection();
        $quantiteColumns = [];

        foreach ($productQuantites as $_) {
            foreach ($_ as $productQuantite) {
                $lenght = count($productQuantite);

                for ($i = 1; $i <= $lenght; $i++) {
                    $quantiteColumns[] = trans('Quantité') . ' ' . $i;
                    $quantiteColumns[] = trans('Date d\'ajout') . ' ' . $i;
                }
            }
        }

        $quantiteColumns = array_unique($quantiteColumns);
        $headings = array_merge($headings, $quantiteColumns, $total);

        return $headings;
    }
    public function map($productQuantite): array
    {
        $result = $this->collection();
    
        $produit = [];
        $maxLength = 0;

        foreach ($result as $r) {
            foreach ($r as $label => $details) {
                $lenght = count($details);
                if ($lenght > $maxLength) {
                    $maxLength = $lenght;
                }
                $totalQuantity = 0;
                $productData = ['product' => $label];
    
                $quantities = [];
                $dates = [];
    
                foreach ($details as $detail) {
                    $quantity = $detail['quantite'];
                    $totalQuantity += $quantity;
    
                    $quantities[] = $quantity;
                    $dates[] = $detail['created_at']->format('d/m/Y H:i');
                }
    
                for ($i = 0; $i < $maxLength; $i++) {
                    $productData['Quantité ' . ($i + 1)] = isset($quantities[$i]) ? $quantities[$i] : '';
                    $productData['Date d\'ajout ' . ($i + 1)] = isset($dates[$i]) ? $dates[$i] : '';
                }
    
                $productData['Total'] = $totalQuantity;
    
                $produit[] = $productData;
            }
        }
        return $produit;
    }
    
    
}
