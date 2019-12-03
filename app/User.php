<?php

namespace App;
use URL;
use Config;
use Carbon\Carbon;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use App\Models\Role;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens,Notifiable,SoftDeletes,Sluggable;
    use EntrustUserTrait { restore as private restoreA; }
    use SoftDeletes { restore as private restoreB; }
    use \HighIdeas\UsersOnline\Traits\UsersOnlineTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug','name','username','phone','ph_country_id','image','cover_image','email','status','is_activated','email_verified_at','password','social_id','social_type'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

     /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

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
            ],
            'username' => [
                'source' => 'name'
            ],
        ];
    }

     /**
     * Check HasRecord in Session table for login user
     */
    public function hasRecordInSession()
    {
       return  $this->hasOne('App\Session','user_id')->where('last_activity', '>=', strtotime(Carbon::now()->subMinutes(Config::get('custom.activity_limit'))));
    }  
    
    /**
    * Return and check online user (by last activity 5 minutes)
    * @return true,false
    */
    public function hasOnline()
    {
       return ($this->hasRecordInSession) ? true : false;
    }  


    /**
     * The attributes that should be has many Roles data.
     */
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role','role_user', 'user_id', 'role_id');
    } 

    public function restore()
    {
        $this->restoreA();
        $this->restoreB();
    }
    
    static public function createUser($data,$role='user')
    {
         $user = static::create([
                    'name' => $data['first_name'].' '.$data['last_name'],
                    'email' => $data['email'],
                    'phone' => isset($data['phone']) ? $data['phone'] : NULL,
                    'ph_country_id' => isset($data['ph_country_id']) ? $data['ph_country_id'] : NULL,
                    'image' => isset($data['profile_pic']) ? $data['profile_pic'] : 'noimage.jpg',
                    'cover_image' => isset($data['cover_image']) ? $data['cover_image'] : 'nocoverimage.jpg',
                    'password' => bcrypt(trim($data['password'])),
                ]);
        $role = Role::findByName($role);
        if(!empty($role))
        {
            $user->roles()->sync([$role->id]);
        }
        return $user;
    }


    public function socialRegister($data,$role='user')
    {
         $user = static::create([
                    'name' => $data['first_name'].' '.$data['last_name'],
                    'email' => $data['email'],
                    'phone' => isset($data['phone']) ? $data['phone'] : NULL,
                    'ph_country_id' => isset($data['ph_country_id']) ? $data['ph_country_id'] : NULL,
                    'image' => isset($data['profile_pic']) ? $data['profile_pic'] : 'noimage.jpg',
                    'cover_image' => isset($data['cover_image']) ? $data['cover_image'] : 'nocoverimage.jpg',
                    'social_id' => $data['social_id'],
                    'social_type' => $data['social_type'],
                    'is_social_login' =>1,
                ]);
        $this->assignRoleOnRegister($user->id);
        $role = Role::findByName($role);
        if(!empty($role))
        {
            $user->roles()->sync([$role->id]);
        }
        return $user;
    }

      /**
     * Assign user Role.
     *
     * @var $request, $userId
     */
    public function assignRoleOnRegister($userId,$role='user')
    {
        $user = static::find($userId);
        $role = Role::findByName($role);
        if(!empty($role))
        {
            $user->roles()->sync([$role->id]);
        }
        return $user;
    } 
   

    /**
     * The function return the full picture path by setter attributes.
     * 
     * @param array $slug 
     */
    public function getPicturePathAttribute()
    {
        return ($this->image) ? URL::to('storage/app/public/users/'.$this->image) : URL::to('storage/users/noimage.jpg');
    }

    /**
     * The function return the full header picture path by setter attributes.
     * 
     * @param array $slug 
     */
    public function getCoverPicturePathAttribute()
    {
        return ($this->cover_image) ? URL::to('storage/app/public/users/'.$this->cover_image) : URL::to('storage/users/noheader.jpg');
    }


    /**
     * The function return the counts of users by setter attributes.
     * 
     * @param array $slug 
     */
    public function getAllTypeUsersCounts($slug)
    {
        return 0;//['followers'=>0,'following'=>0];
    }
    
}
