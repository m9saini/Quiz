<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class UserFollow extends Model
{
     protected $table = "user_follow";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user1','user2'];

    /**
     * Get the User update profiles validation rules.
     *
     * @return array
     */
    public function followRules()
    {
       return [
            'user1'=>'required|number',
            'user2' => 'required|number',
        ];
    }

    public static function updateFollowsCount($userid,$ftype,$type){
        if(in_array($ftype, ['followers','following'])){
            $users=User::find($userid);
            if($users){

                    if($type){
                        $users->$ftype=((int)$users->$ftype)+1;
                    }else{
                        $users->$ftype=((int)$users->$ftype)-1;
                    }
                    $users->save();
                }
        }
    }

   public static function followRequest($u1,$u2){

    $rValue['msg']='Already Requested.';
    $model=UserFollow::where('user1',$u1)->where('user2',$u2)->first();
    if($model){ 
        if($model->u1status)
            $rValue['msg']='Already following.';
        return $rValue;
    }else{
        $model=UserFollow::where('user1',$u2)->where('user2',$u1)->first();
    }

    
    if(empty($model)){ 
        $model = new UserFollow();
        $model->user1=$u1;
        $model->user2=$u2;
        $model->u1status=0;
        $rValue['msg']='Successfully Request Send.'; 
    }else{ 

        if(is_null($model->u2status)){              
            $model->u2status=0;
            $rValue['msg']='Successfully Request Send.';            
        }else if($model->u2status){
            $rValue['msg']='Already following.'; 
        } 
    }
    $model->save();
    return $rValue;
   }

   public static function acceptRequest($u1,$u2){

    $userid='';
    $rValue['msg']='Request Accepted.';
    $model=UserFollow::where('user1',$u1)->where('user2',$u2)->first();
    if($model){ 
        if($model->u1status==0){
            $model->u1status=1;
            $userid=$u1;
        }
    }else{
        $model=UserFollow::where('user1',$u2)->where('user2',$u1)->first();
         if($model && $model->u2status==0){
            $model->u2status=1;
            $userid=$u2;
        }
    }
    if($model){ 
        if(!empty($userid)){ 
            $model->save();
            if($model->u1status==1 && $model->u2status==1){ 
              UserFollow::updateFollowsCount($u1,'followers',true);
              UserFollow::updateFollowsCount($u2,'followers',true);
              $fid=($userid==$u1)?$u2:$u1;
              UserFollow::updateFollowsCount($fid,'following',true);  
            }else{
                UserFollow::updateFollowsCount($userid,'following',true);
            }
        }else{
            $rValue['msg']='Request not accepted.';
        }

    }else{
        $rValue['msg']='Request not accepted.';
    }
    return $rValue;
   }

   public static function unFollow($u1,$u2){

    $userid='';
    $rValue['msg']='Successfully unfollow';
    $model=UserFollow::where('user1',$u1)->where('user2',$u2)->where('u1status',1)->first();
    if($model){ //echo '1';
        $model->u1status=0;
        $userid=$u1;
    }else{
        
        $model=UserFollow::where('user1',$u2)->where('user2',$u1)->where('u2status',1)->first();
        if($model){ //echo '2';
          $model->u2status=NULL;
          $userid=$u2;  
        }
    }
    if($model){ 
        if(!empty($userid)){ 
            $model->save();
            if($model->u1status==0 && is_null($model->u2status)){
              UserFollow::updateFollowsCount($u1,'followers',false);
              UserFollow::updateFollowsCount($u2,'followers',false);
              $fid=($userid==$u1)?$u2:$u1;
              UserFollow::updateFollowsCount($fid,'following',false); 
            }else{ 
                UserFollow::updateFollowsCount($userid,'following',false);
            }
        }else{
            $rValue['msg']='Please try again.';
        }
    }else{
        $rValue['msg']='Please try again.';
    }
    return $rValue;
   }
}