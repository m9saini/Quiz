<?php

namespace App\Http\Controllers\Api\Auth;

use App\User;
use App\Settings;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Http\Requests\API\LoginRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\BaseController;

class RegisterController extends BaseController
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
    public function register(Request $request)
    { 
            $errors =  $this->validator($request->all(), $this->rules());
            $data['status_code'] = 400;
            if($errors){
                $data['error'] = $errors->original;
                return $data;
            }else{
                
                $user =  $this->User->createUser($request->all(),$request->get('role'));
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
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = $this->User->createUser($data);
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
            'phone' => 'required|numeric|min:10',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
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
