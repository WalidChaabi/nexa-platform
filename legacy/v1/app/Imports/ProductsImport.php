<?php

namespace App\Imports;

use App\Models\Menu;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Product;
use App\Services\CategorieService;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ProductsImport implements ToCollection,WithDrawings
{
    protected $store_id;
    protected $CategorieService;
    public function __construct($store_id)
    {
        $this->store_id = $store_id;
        $this->CategorieService = new CategorieService;
    }           

    public function collection($rows)
    {
        $rows = $rows->filter(function ($row) {
            return !empty(array_filter($row->toArray()));
        }); 
        unset($rows[0]);
        if ($rows->first()->count() == 6) {
            $rows->groupBy(5)->map(function ($row,$key) {
                $menu =  $this->CategorieService->create([ 'store_id'=>$this->store_id,'name'=>$key]);
                $row->map(function ($el) use($menu) {
                    Product::create([
                        'store_id' => $this->store_id,
                        'categorie_id' => $menu->id,
                        'label' => $el[0],
                        'price' => $el[1],
                        'promotion_price' => $el[2],
                        'quantite' => $el[3],
                    ]);   
                });
            });
        } else {
            $menu = [];
            foreach ($rows as $key => $row) {
                $product = Product::create([
                    'store_id' => $this->store_id,
                    'label' => $row[0],
                    'price' => $row[1],
                    'promotion_price' => $row[2],
                    'quantite' => $row[3],
                ]);
            
                array_push($menu, $product);
            }
        }
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('/img/products/'.$this->store_id.'/vir.png'));
        $drawing->setHeight(90);
        $drawing->setCoordinates('B3');

        return $drawing;
    }
}
