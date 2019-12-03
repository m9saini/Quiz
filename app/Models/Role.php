<?php

namespace App\Models;

use Zizaco\Entrust\EntrustRole;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\SoftDeletes;
class Role extends EntrustRole
{
    use Sluggable, SoftDeletes,SluggableScopeHelpers;


    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    protected $fillable = ['slug','name','display_name','description','created_at'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */

    protected $dates = ['deleted_at']; 
    
      protected $table = 'roles';
    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name',
                'onUpdate'=>false
            ]
        ];
    }

    /**
     * The function that should be add for this model.
     * 
     * @param array $request 
     */
    static public  function handle($request)
    {
        $role= static::firstOrNew (["name"=>trim($request->name),"display_name"=>trim($request->display_name)]);
        $role->name=trim(strtolower($request->name));
        $role->display_name=trim($request->display_name);
        $role->description=strip_tags($request->description);
        $role->save();
    }

    /**
     * The function that should be update for this model.
     * 
     * @param array $request 
     */
    static public  function UpdateRole($request,$id)
    {
        $role= static::findorFail($id);
        $role->name=trim(strtolower($request->name));
        $role->display_name=$request->display_name;
        $role->description=strip_tags($request->description);
        $role->save();
    }

    /**
     * The function that should be find the slug for this model.
     * 
     * @param array $slug 
     */
    static public function findBySlug($slug)
    {
        return static::where('slug',$slug)->first();
    }

    static public function findByName($name)
    {
        return static::where('name',$name)->first();
    }
    /**
     * The function that should be change status for this model.
     * 
     * @param array $request 
     */
    static function RoleStatus($id,$status)
    {
        $role= static::findorFail($id);
        $role->status=$status;
        $role->save();
    }
    
    /**
     * The function that should be Belongs to user.
     */
    public function users()
    {
        return $this->belongsToMany('App\User');
    }
    
    /**
     * The function that should be Belongs to Permission.
     */
    public function Permissions()
    {
        return $this->belongsToMany('App\Models\Permission', 'permission_role', 'role_id', 'permission_id');
    }
}