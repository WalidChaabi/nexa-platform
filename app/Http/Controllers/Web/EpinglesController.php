<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Epingles;

class EpinglesController extends Controller
{
    public function show()
    {
       $epingles = Epingles::find(request()->id);
        return $epingles;
    }
    public function store(Request $request)
    {  
        request()->validate([
            'store_id'  => 'required|exists:stores,id',
            'name' => 'required|string',
            'color'  => 'required|string',
        ]);

        Epingles::create($request->only(['store_id', 'name', 'color']));

        return redirect()->back();
    }
    public function update(Request $request)
    {  
        $epingles = Epingles::find(request()->id);
        request()->validate([
            'store_id'  => 'required|exists:stores,id',
            'name' => 'required|string',
            'color'  => 'required|string',
        ]);

        $epingles->update($request->only(['store_id', 'name', 'color']));

        return response('UPDATED SUCCESSFULLY', 200);
    }

    public function destroy()
    {
        Epingles::find(request()->id)->delete();
        return response('DELETED SUCCESSFULLY', 200);
    }
}
