<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\models\Table;
use App\Models\TableProduct;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;

class TableController extends Controller
{
    function index()
    {
        $tables = Table::get();
        $fullTables = Table::where('table_status', 1)->get();
        return view('employe.table.create', compact('tables', 'fullTables'));
    }

    function store(Request $request)
    {
        $validator = request()->validate([
            'store_id' => 'required|integer|exists:stores,id',
            'number' => 'required|integer|unique:tables,number',
            'description' => 'required|string',
        ], [
            'number.unique' => 'Ce numéro est déjà pris',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $achat = Table::create($request->only(['store_id', 'number', 'description']));
        return back();
    }

    function StoreTableProduct(Request $request)
    {

        $table_id = $request['table_id'];
        $TableProduct = TableProduct::where([['table_id',$request['store_id']],['status',1]]);
        foreach ($request['products'] as $productTable) {
            if (!isset($productTable[1])) {
                TableProduct::create([
                    'product_id' => $productTable[0],
                    'table_id' => $request['table_id'],
                    'store_id' => $request['store_id'],
                    'status' => 0
                ]);
            }
        }
        $table = Table::findOrFail($table_id);
        $table->update(['table_status' => 1]);
        return Response()->Json(['success' => true]);
    }
    function DeleteProduct(Request $request)
    {
        $table = TableProduct::where([
            ['table_id', $request['table_id']],
            ['product_id', $request['product_id']]
        ])->first();
        $table->destroy();
    }

    function GetTableProducts(Request $request)
    {
        $table_id = $request['table_id'];
        $products = [];
        $tableProducts = TableProduct::where([
            ['table_id', $table_id], ['status', 0]
        ])->get();
        foreach ($tableProducts as $tableProduct) {

            $productData = [
                'table_id' => $tableProduct->id,
                'product_table' => $tableProduct->ProductTable,
            ];
            $products[] = $productData;
        }
        return $products;
    }
    function updateTableStatus(Request $request)
    {
        $table_id = $request['table_id'];
        $tableProducts = TableProduct::where([
            ['table_id', $table_id], ['status', 0]
        ])->get();
        foreach ($tableProducts as $tableProduct) {
            $tableProduct->update(['status' => 1]);
        }
        $table = Table::findOrFail($table_id);
        $table->update(['table_status' => 0]);

        return Response()->Json(['success' => true]);
    }
    function tansferer(Request $request)
    {

        $full_table_id = $request->full_table_id;
        $empty_table_id = $request->empty_table_id;
        if ($empty_table_id == null || $full_table_id == null) {
            return Response()->json(['mesg' => 'choisir une table']);
        } {
            $table = Table::where('id', $full_table_id)->first();
            $new_table = Table::where('id', $empty_table_id)->first();
            $table->update(['table_status' => 0]);
            $new_table->update(['table_status' => 1]);
            $tableProducts = TableProduct::where([
                ['table_id', $full_table_id]
            ])->get();
            foreach ($tableProducts as $tableProduct) {
                $tableProduct->update(['table_id' => $empty_table_id]);
            }
        }
        return back();
    }
}
