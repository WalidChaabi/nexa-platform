<?php 
namespace App\Repositories\Contracts;

use phpDocumentor\Reflection\Types\Integer;

/**
 * Interface InterfaceRepository
 * @package App\Repositories
 */

interface StoreRepositoryInterface {

    /**
     * @return \Illuminate\Support\Collection
     */
    public function all();

   

}