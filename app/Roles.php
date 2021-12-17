<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $fillable = ['name', 'modules_permission'];

    public function users() {
        return $this->belongsTo('App\User','role_id');
    }

    public function hasAccess(array $permissions){
        foreach ($permissions as $permission) {
            if($this->hasPermission($permission)){
                return true;
            }
        }
        return false;
    }

    protected function hasPermission(string $permission){
        $permissions = json_decode($this->modules_permission,true);
        foreach($permissions as $check){
            if($check==$permission){
                return true;
            }
        }
        return false;
    }
}
