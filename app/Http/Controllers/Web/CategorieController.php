<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Product;
use App\Services\CategorieService;

class CategorieController extends Controller
{

    protected $categorieService;

    public function __construct()
    {
        $this->categorieService = new CategorieService;
    }
    public function index()
    {
        return 'Comming soon';
        return view('admin.menus.index');
    }

    public function create()
    {
        $categories = $this->categorieService->all();
        return view('employe.categorie.create', compact('categories'));
    }

    public function store(Request $request)
    { 
        $request->validate([
            'store_id'=>'',
            'name'=>'string|required',
            'description'=>'string|required',
        ]);
        Categorie::create($request->only(['store_id', 'name', 'description']));

        return back();
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        
    }

    public function update(Request $request, $id)
    {
        // dd($request);
        $categorie = Categorie::find($id);

        $request->validate([
            'store_id'=>'',
            'name'=>'string|required',
            'description'=>'string|required',
        ]);
        $categorie->update($request->only(['store_id', 'name', 'description']));
        return back();

    }

    public function destroy($id)
    {
        $categorie = Categorie::find($id);
        $products = Product::where('categorie_id',$id)->get();
        foreach($products as $product){
            $product->delete();
        }
        $categorie->delete();
        return back();
    }
}
