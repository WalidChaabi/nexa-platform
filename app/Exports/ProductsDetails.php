<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Product;

class ProductsDetails implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $storeId;

    public function __construct($storeId)
    {
        $this->storeId = $storeId;
    }

    public function collection()
    { 
        return Product::where('store_id',$this->storeId)->get();
    }

    public function headings(): array
    {
        return [
            trans('Produits'),
            trans('Quantité du stock'),
            trans('Quantité vendue'),
            trans('Prix de vente'),
            trans('Prix d\'achat'),
            trans('Total de vente'),
            trans('Stock theorique'),
            trans('Marge brute'),
            trans('Total brute'),
        ];
    }

    public function map($product): array
    {
        $margeBrute = $product->price - $product->promotion_price;
        $totalBrute = $margeBrute * $product->qte_total;

        return [
            $product->label,
            $product->quantite,
            $product->qte_total,
            $product->price,
            $product->promotion_price,
            $product->price * $product->qte_total,
            $product->quantite - $product->qte_total,
            $margeBrute,
            $totalBrute . ' ' . trans('Mad'),
        ];
    }
}
