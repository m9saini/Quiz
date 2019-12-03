<?php

namespace App\Models;

use Zizaco\Entrust\EntrustPermission;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
//use Illuminate\Database\Eloquent\SoftDeletes;
class Permission extends EntrustPermission
{
    use Sluggable, SluggableScopeHelpers;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'display_name','description'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
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
        $permission= static::firstOrNew (["name"=>$request->name,"display_name"=>$request->display_name]);
        $permission->name=$request->name;
        $permission->display_name=$request->display_name;
        $permission->description=$request->description;
        $permission->group_name=$request->group_name;
        $permission->save();
    }
    
    /**
     * The function that should be add for this model.
     * 
     * @param array $request 
     */
    static public  function handleSubmit($request)
    {
        $permission= static::firstOrNew (["name"=>$request['name'],"display_name"=>$request['display_name']]);
        $permission->name=$request['name'];
        $permission->display_name=$request['display_name'];
        $permission->description=$request['description'];
        $permission->group_name=$request['group_name'];
        $permission->save();
    }
    
    /**
     * The function that should be update for this model.
     * 
     * @param array $request 
     */
    public  function UpdatePermission($request='',$id='')
    {
        $permission= static::findorFail($id);
        $permission->display_name=$request->display_name;
        $permission->description=$request->description;
        $permission->group_name=$request->group_name;
        $permission->save();
    }

    /**
     * The function that should be find the slug to data for this model.
     * 
     * @param array $slug 
     */
    static public function findBySlug($slug)
    {
        return static::where('slug',$slug)->first();
    }

    /**
     * The function that should be status change for this model.
     * 
     * @param array $request 
     */
    static function PermissionStatus($id,$status)
    {
        $permission= static::findorFail($id);
        $permission->status=$status;
        $permission->save();
    }

     /**
     * The function that should be status change for this model.
     * 
     * @param array $request 
     */
    public function getPermissionRouteLists()
    {
        return static::pluck('name','name');
    }
}