<?php
namespace App\Services;

use App\Models\CrossProduct;

class CrossProductService
{

public function create(array $crossProduct)
{
   return  CrossProduct::create($crossProduct);
}

public function edit(int $crossProductId,$newProduct){
    $oldCrossProduct = $this->find($crossProductId);
    $oldCrossProduct->update($newProduct);
    $oldCrossProduct->refresh();
    return $oldCrossProduct;

}

public function find(int $crossProductId)
{
    return CrossProduct::find($crossProductId);
}

public function delete(int $crossProductId)
{
    CrossProduct::destroy($crossProductId);
}

public function all()
{
    return CrossProduct::orderBy('id','desc')->with('product')->with('sms')->get();
}

}
