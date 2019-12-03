<?php

namespace App\Http\Controllers\Api\User;

use App\User;
use App\Settings;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\Http\Controllers\Api\BaseController;

class UserController extends BaseController
{
    
      public function __construct(User $User)
      {
         $this->User = $User;
      }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    { 
            /*$header= $request->header('Authorization');
            $data=$this->getUser($request->get('user_id'),$request);
            dd($data); die;*/
            $filleable = app('request')->only('user_slug','first_name','last_name', 'location','gender');
            $validator = app('validator')->make($filleable, $this->updateProfileRules());
            if ($validator->fails()) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Could not update user profile.', $validator->errors());
            }else{

                $user=$request->user();
                dd(Auth::user());
                if($this->getUser($request->get('user_slug'),$request) ==false) return $this->ValidateHeader();
                $user_id = $this->getUser($request->get('user_slug'),$request);
                /*$emailVarify =  $this->validator($request->all(), $this->emailValidiation($user_id));
                if($emailVarify){
                     $data['data'] = $emailVarify->original;
                       return $data;
                }*/
                $user = $this->User->findorFail($user_id);
                $user->update($filleable);
                if($user){
                    $response['status_code'] = 200;
                    $response['message'] = 'Profile updated successfully';
                    //$response['data']['token_type'] = 'Bearer';
                   // $response['data']['access_token'] = trim($user->api_token);
                    $response['data']['user_id'] = $user['id'];
                    $response['data']['slug'] = trim($user['slug']);
                    $response['data']['username'] = trim($user['username']);
                    $response['data']['first_name'] = $user['name'];
                    $response['data']['last_name'] = $user['last_name'];
                    $response['data']['gender'] = $user['gender'];
                    $response['data']['email'] = trim($user['email']);
                    $response['data']['profile_pic'] = $user->picture_path;
                    $response['data']['location'] = $user->location;
                    $response['data']['created_at'] = trim($user['created_at']);
                }else{
                     $response['status_code'] = 500;
                     $response['message'] = 'Profile not updated successfully';
                }
            } 
            return $this->response->array($response,500);
        
    }



    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function details()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], 200);
    }
    /**
     * Get the User update profiles picture media_id after upload picture validation rules.
     * @param file
     * @return $media_id
     */
    public function updateProfilePicture(Request $request)
    {
        $errors =  $this->validator($request->all(), $this->updateProfilePictureRules());
            $data['status_code'] = 400;
            if($errors){
                $data['data'] = $errors->original;
                return $data;
        }
        $destinationPath = '/uploads/media/profile/'; 
        $mediaId = $this->Media->saveMedia($request,$destinationPath);
         return response()->json(["status_code"=>200,"media_id"=>$mediaId,'message'=>'Profile picture updated successfully']);
    }

    /**
     * Get the User update profiles validation rules.
     *
     * @return array
     */
    protected function updateProfileRules()
    {
       return [
            'user_slug'=>'required',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'gender' => 'required|in:male,female,others',
           // 'email' => 'required|string|email|max:255',
        ];
    } 

    /**
     * Get the User update profiles validation rules.
     *
     * @return array
     */
    protected function updateProfilePictureRules()
    {
       return [
            'files' => 'required|image:jpg,jpeg,png',
        ];
    }

    /**
     * Get the User email profiles validation rules.
     *
     * @return array
     */
    protected function emailValidiation($id=NULL)
    {
       return [
            'email'=>"required|unique:users,email,$id",
            //'username'=>"required|unique:users,username,$id",
        ];
    }
 
    /**
     * Get the Login validation rules.
     *
     * @return array
     */
    public function validator($request,$rules)
    {
        $validator = Validator::make($request, $rules);
            if ($validator->fails()) {
                foreach ($validator->messages()->toArray() as $name =>$error) {
                    $errors[$name] =  $error[0];
                }
              return response(
                  $errors,
                  500
              );
            }
    }

    // Validate user by slug and Access Token
    public function getUser($slug,$request)
    {
        $token_user= $this->getUserByAceesToken($request);
        $user = User::where('slug',$slug)->first();
        if(empty($user)) return false;
        if($user->slug) return $user->id;
        if($token_user->slug ==$user->slug) return $token_user->id;
        return false;
    } 

    public function getUserByAceesToken($request)
    {
        return $request->user();
    }

   /**
   * return unathentication message with error code 500
   * @return \Illuminate\Http\Response
   */
    public function ValidateHeader()
    {
        return $this->response->array(["message"=>"Unauthenticated","status_code"=>500])->setStatusCode(500);
    }


    
}
