<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\User;
use App\UsersKyc;
use App\UserFollow;
use App\UserAccount;
use Modules\StaticPages\Entities\StaticPages;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ApiController extends BaseController
{
    
      public function __construct(Request $request,User $User,UsersKyc $UsersKyc,UserFollow $UserFollow,UserAccount $useraccounts)
      {
         $this->User = $User;
         $this->UsersKyc = $UsersKyc;
         $this->UserFollow = $UserFollow;
         $this->UserAccount=$useraccounts;
      }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try
        {
            if(md5("MobileAppAPI")!=$request->headers->get('mobileappapi')) return $this->ValidateHeader();
            $user = User::find(1);
            $token = $user->createToken('MobieAPI')->accessToken;
            return $this->response->array(["token_type"=>"Bearer","access_token"=>$token]);
        }
        catch (\Exception $e)
        {
            return $this->ValidateHeader();
        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexSlug(Request $request,$slug)
    {
        try
        {
            //if(md5("MobileAppAPI")!=$request->headers->get('mobileappapi')) return $this->ValidateHeader();
            $user = $this->User->findBySlug($slug);
            $token = $user->createToken('MobieAPI')->accessToken;
            return $this->response->array(["token_type"=>"Bearer","access_token"=>$token]);
        }
        catch (\Exception $e)
        {
            return $this->ValidateHeader();
        }

    }

  /**
   * return app version and staging of domain and url.
   * @return \Illuminate\Http\Response
   */
  public function checkVersion($type=NULL)
  {
     try
        {
          if($type)
          {
              //return app version-code url
              //$setting = $this->Settings->getAppVersion($type);
              $setting = 1;
              $response['status_code'] = 200;
              //$response['version_code']=(!empty($setting))?$setting->key_value:0; 
              $response['version_code']=0; 
            if(!$setting){
               $response['status_code'] = 500;
               $response['version_code']='Invalid device type' ;
            }
          }
          else
          {
            $response['status_code']  = 500;
            $response['version_code'] = "Type is missing";      
          }
            //return app running mode url
            $response['url']= env('APP_STAGING_URL');
            //$response['url']= $this->Settings->getApplicationUrl();
            return $this->response->array($response);
        }catch (\Exception $e)
          {
          return $this->ValidateHeader();
        }
  }


   /**
   * return static pages data with meta tag.
   * @return \Illuminate\Http\Response
   */
  public function getPages(Request $request,$slug=NULL)
  {
     try
        {
          if($slug){
              $response['status_code']  = 200;
              $pages=StaticPages::findBySlug($slug);
              if($pages){
                $response['message']='Page Details';
                $response['data']=$pages;
              }else{
                $response['status_code']  = 500;
                $response['message']='Invalid Page Url';
              }
          }else{
            $response['status_code']  = 500;
            $response['message']='Slug requried';
          }
          return $this->response->array($response);
        }catch (\Exception $e)
          {
          return $this->ValidateHeader();
        }
  }


    /**
   * return static pages data with meta tag.
   * @return \Illuminate\Http\Response
   */
  public function kycUpsert(Request $request,$slug=NUll)
  {
     try
        {

              $response['status_code']  = 200;
              switch ($slug) {
                case 'upload':
                 $response=$this->addKyc($request);
                  break;
                case 'edit':
                  # code...
                  break;
                case 'delete':
                  # code...
                  break;
                default:
                  $response['status_code']  = 500;
                  $response['message']='Invalid url';
                  break;
              }
          
          return $this->response->array($response);
        }catch (\Exception $e)
          {
          return $this->ValidateHeader();
        }
  }

  public function addKyc($request){

    $errors =  $this->validator($request->all(), $this->UsersKyc->kycRules());
      $response['status_code'] = 400;
      $response['message']='Please try again';
      if($errors){
          $response['error'] = $errors->original;
          return $response;
      }else{
        $user=Auth::user(); 
    $kyc= new UsersKyc();
    $isexits= UsersKyc::where('user_id',$request->get('user_id'))->where('kyc_type',$request->get('kyc_type'))->first();
    if(!$isexits){
    $filename = upload($request->file('doc_image'),storage_path() . '/app/public/kyc/');
    $kyc->user_id= $request->get('user_id');
    $kyc->doc_image= $filename;
    $kyc->doc_number= $request->get('doc_number');
    $kyc->kyc_type= $request->get('kyc_type');
      if($kyc->save()){
        $response['status_code'] = 200;
        $response['message']='KYC Document Uploaded';
      }
    }else{
      $response['message']='Already added '.$kyc->kycTypeList($request->get('kyc_type'));
    }
   return $response;
  }
}
  
  public function userAccountUpsert(Request $request,$slug=NUll)
  {
     try
        {

              $response['status_code']  = 200;
              switch ($slug) {
                case 'add':
                case 'update': 
                 $response=$this->upsertAccount($request,$slug);
                  break;
                case 'details':
                 $response=$this->getAccountDetails($request);
                  break;
                default:
                  $response['status_code']  = 500;
                  $response['message']='Invalid url';
                  break;
              }
          
          return $this->response->array($response);
        }catch (\Exception $e)
          {
          return $this->ValidateHeader();
        }
  }

  public function upsertAccount($request,$slug){
      $isexits=true;
      $id=NULL;
      $checkId='';
      if($slug=='update'){
        $id=$request->get('id');
        $checkId=1;
      }
      $errors =  $this->validator($request->all(), $this->UserAccount->accountRules($checkId));
        $response['status_code'] = 400;
        $response['message']='Please try again';
        if($errors){
            $response['errors'] = $errors->original;
            if (isset($errors->original['ifsc_code']))
                $response['errors']['ifsc_code']='Invalid IFSC code.';
            return $response;
        }else{
      if($id){
        $acc= UserAccount::find($id);
        $isexits=NULL;
      }else{
        $acc= new UserAccount();
        $isexits= UserAccount::where('user_id',$request->get('user_id'))->where('acc_number',$request->get('acc_number'))->first();
      }
      if(!$isexits && $acc){
      $response['status_code'] = 500;       
      $acc->user_id= $request->get('user_id');
      $acc->acc_number= $request->get('acc_number');
      $acc->ifsc_code= $request->get('ifsc_code');
      $acc->bank_name= $request->get('bank_name');
      $acc->bank_branch= $request->get('bank_branch');
      $acc->acc_holder_name= $request->get('acc_holder_name');
        if($acc->save()){      
          $response['status_code'] = 200;
          $msg=(!empty($id))?'updated':'added';
          $response['message']='Account '.$msg.' successfully';
        }else{
          $response['message']='Account not added';
        }
      }else{ 
        $msg=(!empty($id))?'not found':'already added';
        $response['message']="This account ".$msg;
      }
     return $response;
    }
  }

  public function getAccountDetails($request){ 
    $user_id=$request->get('user_id');
    $id=$request->get('id');
    $user=UserAccount::where('user_id',$user_id)->where('id',$id)->first();
    if($user){
      $response['code']=200;
      $response['message']='Account Details.';
      $response['data']=$user;
    }else{
      $response['code']=500;
      $response['message']='Invalid Account';
    }
    return $response;
  }

  public function userFollowUpdate(Request $request,$slug=NUll){
  try
      {

      $response['status_code']  = 200;
      $user1=(int)$request->get('user_id');
      $user2=(int)$request->get('fid');
      if($user1 && $user2 && $user1!=$user2){
          switch ($slug) {
            case 'followrequest':
            $rst=UserFollow::followRequest($user1,$user2);
            $msg=$rst['msg'];
              break;
            case 'followaccept':
            $rst=UserFollow::acceptRequest($user1,$user2);
            $msg=$rst['msg'];
              break;
            case 'unfollow':
              $rst=UserFollow::unFollow($user1,$user2);
              $msg=$rst['msg'];
              break;
            default:
              $response['status_code']  = 500;
              $msg='Invalid url';
              break;
          }
          $response['message']=$msg;
        }else{
            $response['status_code'] = 400;
            $response['message']='Please try again';
            if(empty($user1))
            $response['errors']['user_id'][] = 'The user_id field is required';      
            if(empty($user2))
            $response['errors']['fid'][] = 'The fid field is required';
            if($user1==$user2)
            $response['errors']['fid'][] = 'The user id and fid are not same value required';
        }
    
        return $this->response->array($response);
      }catch (Exception $e)
        {
        return $this->ValidateHeader();
      }

  } 


  /*public function userFollowUpsert($request,$type){
      $user1=(int)$request->get('user_id');
      $user2=(int)$request->get('fid');
      if($user1 && $user2){

          $model=UserFollow::updateFollow($user1,$user2);
          $model->doc_number= $request->get('doc_number');
          $model->status= $request->get('kyc_type');
            if($model->save()){
              $response['status_code'] = 200;
              $response['message']='KYC Document Uploaded';
            }else{
            $response['message']='Already added '.$kyc->kycTypeList($request->get('kyc_type'));
          }
        
      }else{
          $response['status_code'] = 400;
          $response['message']='Please try again';
          if(empty($user1))
          $response['errors']['user_id'][] = 'The user_id field is required';      
          if(empty($user2))
          $response['errors']['fid'][] = 'The fid field is required';
          
      }
      return $response;
  }*/
  /**
     * Get the validation rules.
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

  /**
   * return unathentication message with error code 500
   * @return \Illuminate\Http\Response
   */
    public function ValidateHeader()
    {
        return $this->response->array(["message"=>"Unauthenticated","status_code"=>500])->setStatusCode(500);
    }

}
