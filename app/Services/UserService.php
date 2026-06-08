<?php

namespace App\Services;

use App\Models\User;

class UserService
{

    public function getOwners(){
        return User::where('is_admin','=',2)->with('stores')->get();
    }    

    public function desactivateAccounts(array $storesId){
       $users =  User::whereIn('store_id',$storesId);
       $users->update(['is_active'=>0]);
    }

    public function desactivateOwnerAccount($ownerId){
        $owner = User::find($ownerId);
        $owner->update(['is_active'=>0]);
        $owner->refresh();
        
        return $owner;
    }

    public function find(int $userId){
        return User::find($userId);
    }

    public function update(int $id,array $newuser){
        $user = $this->find($id);
        $user->update($newuser);
        $user->refresh();
        return $user;
    }
   
}
