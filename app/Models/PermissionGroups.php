<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermissionGroups extends Model
{
    use SoftDeletes;


    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    protected $fillable = ['slug','name','description'];

    
    public function createPermissionGroup($string='')
    {
        if($string){
            $data = explode('.',$string);
            array_pop($data);
            $slug  = implode('.',$data);
            $gName = implode(' ',$data);
            $group = static::where('slug',$slug)->count();
            if($group == 0)
            {
                if($slug){
                   static::create([
                        'slug' => trim($slug),    
                        'name' => ucwords($gName)
                    ]); 
                }
            }
            return $slug;
        }
    }
}