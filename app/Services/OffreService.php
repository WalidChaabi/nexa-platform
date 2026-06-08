<?php 
namespace App\Services;

use App\Models\Offre;

class OffreService  {


      /**
     * @return collection $offres
     */
    public function getAll()
    {
        return  $offres =  Offre::OrderBy('id','DESC')->get();
    }
    /**
     * create a new offre Object
     * @param offre $offre
     * @return Collection
     */
    public function createoffre($offre)
    {
         return  $offre = Offre::create($offre);
    }
    /**
     * @param offre $offre
     * @return Collection
     */
    public function updateoffre($offre,$data)
    {
        return $offre->update($data);
    }

    /**
     * @param string $id
     * @return Collection
     */
    public function getoffreById($id)
    {
        return Offre::findOrFail($id);
    }



}