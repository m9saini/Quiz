<?php

namespace App\Http\Controllers\Api\Auth;

use App\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Http\Requests\API\LoginRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\BaseController;
use Carbon\Carbon;

class AuthenticationController extends BaseController
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
    public function login(Request $request)
    { 
            try{
                if(env('API_APIAUTHMD5') !=$request->headers->get('mobileappapi')) return $this->ValidateHeader();
                $errors =  $this->validator($request->all(), $this->rules());
                $data['status_code'] = 400;
                if($errors){
                    $data['error'] = $errors->original;
                    return $data;
                } 

              $chkEmail = $this->User->where('email',$request->get('email'))->first();
              $response['status_code'] = 200;
              if($chkEmail){
                  if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
                      $user = Auth::user();
                      $lastlogin=User::find(Auth::user()->id);
                      $lastlogin->lastlogin=Carbon::now();
                      $lastlogin->save();
                      $token = $user->createToken('MobieAPI')->accessToken;
                      $response['message'] = 'Successfully login';
                      $response['data']['token_type'] = 'Bearer';
                      $response['data']['access_token'] = trim($token);
                      $response['data']['user_id'] = trim($user['id']);
                      $response['data']['slug'] = trim($user['slug']);
                      $response['data']['username'] = trim($user['username']);
                      $response['data']['name'] = trim($user['name']);
                      $response['data']['email'] = trim($user['email']);
                      //$response['data']['media_id'] = trim($user['media_id']);
                      $response['data']['profile_pic'] = $user->picture_path;
                      $response['data']['created_at'] = trim($user['created_at']);
                  }else{
                      $response['status_code'] = 400;
                      $response['message'] = 'Password not match';
                  }
                }else{
                  $response['status_code'] = 400;
                  $response['message'] = 'Email do not match in record';
                }
                return response()->json($response,$response['status_code']);
            }
            catch (\Exception $e)
            {
                return $this->ValidateHeader();
            }
    }


  
    /**
     * Get the Login validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'password' => 'required|min:6',
            'email'=>"required|email"
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
