<?php

namespace App\Http\Controllers\Api\Auth;

use App\User;
use App\Setting;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\BaseController;

class SocialLoginController extends BaseController
{
    
      public function __construct(User $User)
      {
         $this->User = $User;
      }

    public function socialLogin(Request $request){

        try{
                
              if(env('API_APIAUTHMD5') !=$request->headers->get('mobileappapi')) return $this->ValidateHeader();
              if(($request->get('social_id') != null) && 
                ($request->get('social_type') != null) ) {
                  $sid=$request->get('social_id');
                  $stype=$request->get('social_type');
                  
              }else{
                $response['status_code'] = 400;
                $response['message'] = 'social_id and social_type required';
                return response()->json($response,$response['status_code']);
              }
              $user = User::where('social_id',$sid)->first(); 
              $response['status_code'] = 200;
              if($user){ 
                     // $savedata=['social_id'=>$sid,'social_type'=>$stype];
                      //$user = $this->User->findorFail($chkUser['id']);
                      //$user->update($savedata);
                      $token = $user->createToken('MobieAPI')->accessToken;
                      $response['message'] = 'Successfully login';
                      $responce['call_social_register']=false;
                      $response['data']['token_type'] = 'Bearer';
                      $response['data']['access_token'] = trim($token);
                      $response['data']['user_id'] = trim($user['id']);
                      $response['data']['slug'] = trim($user['slug']);
                      $response['data']['username'] = trim($user['username']);
                      $response['data']['name'] = trim($user['name']);
                      $response['data']['email'] = trim($user['email']);
                      $response['data']['profile_pic'] = $user->picture_path;
                      $response['data']['created_at'] = trim($user['created_at']);
                 
                }else{
                  $response['call_social_register']=true;
                  $response['message'] = 'First Time User';
                }
                return response()->json($response,$response['status_code']);
            }
            catch (\Exception $e)
            {
                return $this->ValidateHeader();
            }

    }
 


    public function register(Request $request)
    { 
            $errors =  $this->validator($request->all(), $this->rules());
            $data['status_code'] = 400;
            if($errors){
                $data['error'] = $errors->original;
                return $data;
            }else{
                
                $user =  $this->User->socialRegister($request->all(),$request->get('role'));
                $token = $user->createToken('MobieAPI')->accessToken;
                $response['status_code'] = 200;
                $response['message'] = 'Successfully registered';
                $response['data']['token_type'] = 'Bearer';
                $response['data']['access_token'] = trim($token);
                $response['data']['user_id'] = $user['id'];
                $response['data']['slug'] = trim($user['slug']);
                $response['data']['username'] = trim($user['username']);
                $response['data']['name'] = trim($user['name']);
                $response['data']['email'] = trim($user['email']);
                $response['data']['profile_pic'] = $user->picture_path;
                $response['data']['created_at'] = trim($user['created_at']);
            } 
            return $this->response->array($response);
    }

    /**
     * Get the Login validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|numeric|min:10|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'social_type' => 'required|string|max:20',
            'social_id'  => 'required|string|max:50|unique:users',
        ];
    }
    /**
     * Get the Login validation rules.
     *
     * @return array
     */
    public function validator($request,$rules)
    {

        $validator = Validator::make($request, $this->rules());
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

  /**
   * return unathentication message with error code 500
   * @return \Illuminate\Http\Response
   */
    public function ValidateHeader()
    {
        return $this->response->array(["message"=>"Unauthenticated","status_code"=>500])->setStatusCode(500);
    }

}
