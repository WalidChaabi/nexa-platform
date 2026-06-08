<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\CrossProductService;
use App\Services\StoreService;

class CrossProductController extends Controller
{
    
    protected $crossProductService;
    protected $storeService;

    public function __construct()
    {
        $this->crossProductService =  new CrossProductService;
        $this->storeService =  new StoreService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.cross-selling.list',['crossProducts'=>  $this->crossProductService->all() ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.cross-selling.create',['stores' => $this->storeService->getAll()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'product_id'=>'required',
            'message'=>'required',
            'days'=>'required',
        ]);
        $this->crossProductService->create($request->only(['product_id', 'message', 'days']));
       return redirect(route('admin.cross-products'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('admin.cross-selling.edit',['stores' => $this->storeService->getAll(),'product'=>$this->crossProductService->find($id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'product_id'=>'required',
            'message'=>'required',
            'days'=>'required',
        ]);
        $this->crossProductService->edit($id, $request->only(['product_id', 'message', 'days']));
       return redirect(route('admin.cross-products'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->crossProductService->delete($id);
        return redirect(route('admin.cross-products'));
    }
}
