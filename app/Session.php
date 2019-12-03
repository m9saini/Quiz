<?php


namespace App;

use Illuminate\Database\Eloquent\Model;


class Session extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'sessions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','ip_address','user_agent','payload','last_activity'
    ];

    public $timestamps = false;


    /**
     * Returns all the guest users.
     *
     * @param  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGuests($query)
    {
        return $query->whereNull('user_id')->where('last_activity', '>=', strtotime(Carbon::now()->subMinutes(Config::get('custom.activity_limit'))));
    }

    /**
     * Returns all the registered users.
     *
     * @param  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRegistered($query)
    {
        return $query->whereNotNull('user_id')->where('last_activity', '>=', strtotime(Carbon::now()->subMinutes(Config::get('custom.activity_limit'))))->with('user');
    }

   /**
     * Returns all the registered users.
     *
     * @param  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function currentUser()
    {
        return $this->whereNotNull('user_id')->where('last_activity', '>=', strtotime(Carbon::now()->subMinutes(Config::get('custom.activity_limit'))));
    }

    /**
     * Updates the session of the current user.
     *
     * @param  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUpdateCurrent($query)
    {
        return $query->where('id', Session::getId())->update([
            'user_id' => ! empty(Auth::user()) ? Auth::id() : null
        ]);
    }

}