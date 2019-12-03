<?php

use App\Models\PermissionGroups;
use App\Models\Role;
use App\Models\Language;
use App\Question;
use App\CategoryDescription;
use App\FaqDescription;
use App\QuestionDescription;
use App\QuestionOption;
use App\Tournament;
use App\UserAppSetting;
/**
 * Global helpers file with misc functions
 **/

if (! function_exists('app_name')) {
	/**
	 * Helper to grab the application name
	 *
	 * @return mixed
	 */
	function app_name() {
		return config('app.name');
	}
}

if ( ! function_exists('access'))
{
	/**
	 * Access (lol) the Access:: facade as a simple function
	 */
	function access()
	{
		return app('access');
	}
}

if ( ! function_exists('javascript'))
{
	/**
	 * Access the javascript helper
	 */
	function javascript()
	{
		return app('JavaScript');
	}
}

if ( ! function_exists('gravatar'))
{
	/**
	 * Access the gravatar helper
	 */
	function gravatar()
	{
		return app('gravatar');
	}
}

if ( ! function_exists('pr'))
{
    /**
     * Access the print_r helper
     */
    function pr($data)
    {
        echo "<pre>";
        print_r($data);die;

    }
}

if ( ! function_exists('setActive'))
{
    /**
     * Access the setActive helper
     */
    function setActive($path)
    {
        return Request::is($path . '*') ? 'nav-active' :  '';
    }
}

if ( ! function_exists('setActiveOpen'))
{
    /**
     * Access the  helper
     */
    function setActiveOpen($path)
    {
        return Request::is($path . '*') ? 'display: block' :  '';
    }
}

if ( ! function_exists('setActiveMainMenu'))
{
    /**
     * currunt menu active
     */
    function setActiveMainMenu($path)
    {
        return Request::is($path) ? 'active' :  '';
    }
}

if ( ! function_exists('addClassForOnlineOffline'))
{
    /**
     * add Class For online Offline
     */
    function addClassForOnlineOffline($status)
    {
        return ($status) ? 'Online' : 'Offline';
    }
}

/*
** Get current Year
*/
if ( ! function_exists('currentYear'))
{
    function currentYear()
    {
        return date('Y');
    }
}

if ( ! function_exists('upload'))
{
    /**
     * Access the upload helper
     */
    function upload($fileName,$path)
    {
          $file = $fileName;
          $destinationPath = $path;
          $extension = $file->getClientOriginalExtension();
          $fileName = time().'.'.$extension;
          $file->move($destinationPath, $fileName);
          return $fileName;
    }
}

if ( ! function_exists('getActiveRoleArray'))
{
    /**
     * getActiveRoleArray
     */
    function getActiveRoleArray()
    {
      return Role::where('status',1)->where('name','!=','admin')->get();
    }
}

/*
** Display Button With Role
*/
if ( ! function_exists('displayButton'))
{
    function displayButton($buttonName=array())
    {
        $return = [];
        if( is_array($buttonName) &&  count($buttonName) > 0)
        {
            foreach ($buttonName as $key => $value)
            {
                $route = $value[0]; // modelName.function
                $routeKey = isset($value[1])?$value[1]:[]; //
                //if(displayButtonPermission($route))
                $class = $routeKey[0];
              //  dd($value);
                $return[$key] = buttonHtml($key, route($route, $routeKey),$class );
            }
        }
        return $return;
    }
}

/*
 * Display button in permission
 */

function displayButtonPermission($route)
{
    if(\Entrust::can($route))
    {
        return true;
    }
    return false;
}


function getWeekDays($index=NULL){
  $days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
  return ($index!='' && isset($days[$index]))?$days[$index]:$days;
}
/*
** Button With Html
*/
if ( ! function_exists('buttonHtml'))
{
    function buttonHtml($key, $link,$class)
    {
        $array = [
            "edit"=>"<a href='".$link."' title='Edit' class='tooltips' data-toggle='tooltip' data-placement='top'><i class='fa fa-pencil'></i></a>",
            "Active"=>'<span class="margin-r-5"> <a id="Inactive_'.$class.'" data-placement="top" data-toggle="tooltip" class="danger tooltips" title="Inactive" rel="Inactive" name="'.$link.'" href="javascript:;" OnChange="return ConfirmDeleteLovi(this.id,this.rel,this.name);" onClick="return AjaxActionTableDrow(this);" data-title="Inactive" data-action="'.$link.'"><i class="fa fa-ban" aria-hidden="true"></i></a>  &nbsp;</span>',
            "Inactive"=>'<span class="margin-r-5"> <a id="Active_'.$class.'" data-toggle="tooltip" class="success tooltips"  title="Active"  rel="Active" name="'.$link.'" href="javascript:;" data-placement="top"  OnChange="return ConfirmDeleteLovi(this.id,this.rel,this.name);" onClick="return AjaxActionTableDrow(this);" data-title="Active" data-action="'.$link.'"><i class="fa fa-check" aria-hidden="true"></i></a> &nbsp;</span>',
            "add"=>'<a href="'.$link.'" class="btn col-md-11  btn-primary">Add</a>',
            "delete"=>'
                <form method="POST" action="'.$link.'" accept-charset="UTF-8" style="display:inline" class="dele_'.$class.'">
                    <input name="_method" value="DELETE" type="hidden">
                    '.csrf_field().'
                        <span>
                             &nbsp;<a href="javascript:;" id="dele_'.$class.'" data-toggle="tooltip" title="Delete" type="button"  data-placement="top" name="Delete" class="delete_action tble_button_st tooltips" Onclick="return ConfirmDeleteLovi(this.id,this.name,this.name);" ><i class="fa fa-trash-o" title="Delete"></i>
                            </a>
                         </span>
                </form>',
            "deleteAjax"=>'&nbsp;&nbsp;<a href="javascript:;" id="dele_'.$class.'" data-toggle="tooltip" title="Delete" data-title="Delete" type="button"  data-placement="top" class="delete_ajax tble_button_st tooltips"  data-action="'.$link.'" onClick="return AjaxActionTableDrow(this);"><i class="fa fa-trash-o" title="Delete"></i></a>',
            "view"=>'<span class="margin-r-5"><a data-toggle="tooltip"  class="" title="View" href="'.$link.'"><i class="fa fa-eye" aria-hidden="true"></i></a> &nbsp;</span>',
             "pages"=>'<span class="margin-r-5"><a data-toggle="tooltip"  class="btn btn-info small-btn" title="View book pages" href="'.$link.'"><i class="fa fa-file-text-o" aria-hidden="true"></i></a></span>',
             "permission"=>'<span class="f-left margin-r-5"> &nbsp;<a class="tble_button_st tooltips" data-toggle="tooltip" data-placement="top" title="Set Permission" href="'.$link.'"><i class="fa fa-cog" aria-hidden="true"></i></a></span>',
             "restore"=>'<span class="margin-r-5"><a id="restore_'.$class.'"  data-toggle="tooltip" data-placement="top" class="warning tooltips" title="Restore" rel="Restore" name="'.$link.'" href="javascript:;" Onclick="return ConfirmDeleteLovi(this.id,this.rel,this.name);"><i class="fa fa-database" aria-hidden="true"></i></a></span>',
             "addon"=>" &nbsp; <a href='".$link."' title='Addon' class='tooltips' data-toggle='tooltip' data-placement='top'><i class='fa fa-plus'></i> </a>"
            ];

        if(isset($array[$key]))
        {
            return $array[$key];
        }
        return '';
    }
}

   /*
** Array In check key exist or not
*/
if ( ! function_exists('keyExist'))
{
    function keyExist($array=array(), $key)
    {
        if(isset($array[$key]))
        {
            return $array[$key];
        }
        else
        {
            return '';
        }
    }
}

if ( ! function_exists('getStatusAI'))
{
    function getStatusAI($status)
    {
        $getStatusArray = getStatusArray();
        if(isset($getStatusArray[$status]))
        {
            return $getStatusArray[$status];
        }
        return '';
    }
}

/*
** Get Status Array
*/
if (! function_exists('getStatusArray')) {

    function getStatusArray() {
        $return = ['1'=>'Active' , '0'=>'Inactive'];        
        return $return;
    }
}
/*
** All type list in array format like key or value
*/
if (! function_exists('getAllList')) {

    function getAllList($table, $key, $value, $status=0) {
        if($status!=0)
        {
            $lists = DB::table($table)
                ->where('deleted_at',null)
                ->where('status',1)
                ->select($key, $value)
                ->get();
        }
        else
        {
            $lists = DB::table($table)
                ->where('deleted_at',null)
                ->select($key, $value)
                ->get();
        }
        if($lists)
        {
            $return = $lists->pluck($value, $key);
        }
        return $return;
    }
}

if ( ! function_exists('getActiveRoleList'))
{
    /**
     * getActiveRoleList
     */
    function getActiveRoleList()
    {
      return Role::where('status',1)->pluck('display_name','name')->toArray();
    }
}

/*
** Get Permission group
*/
if ( ! function_exists('getPermissionGroup'))
{
    function getPermissionGroup()
    {
        return PermissionGroups::where('status',1)->pluck('name','slug');
    }
}

if ( ! function_exists('getPhoneCodeList'))
{
    /**
     * getPhoneCodeList
     */
    function getPhoneCodeList()
    {
      return \App\Country::pluck('countryName','code')->toArray();
    }
}

if (! function_exists('formatSales')) {
    function formatSales($sales){
        if ($sales >= 1000 && $sales < 1000000) {
            $n_format = number_format($sales/1000). 'K';
        } else if ($sales >= 1000000 && $sales < 1000000000) {
            $n_format = number_format($sales/1000000).'M';
        } else if ($sales >= 1000000000) {
            $n_format=number_format($sales/1000000000).'B';
        } else {
            $n_format = number_format($sales);
        }
        return $n_format;
    }
}

if (!function_exists('paginationFormat')) {
    /**
     * return data according paginate.
     *
     * @return array
     */
      function paginationFormat($request)
     {
          $res['lastPage'] = $request->lastPage();
          $res['total'] = $request->total();
          $res['nextPageUrl'] = ($request->nextPageUrl()) ? $request->nextPageUrl() : "";
          $res['prevPageUrl'] = ($request->previousPageUrl()) ? $request->previousPageUrl() : "";
          $res['currentPage'] = $request->currentPage();
          return $res;
     }
}


if (!function_exists('getCustomString')) {
  function getCustomString($length = 10) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
  }
}

if (!function_exists('getLanguageList')) {
  function getLanguageList($list=null,$code=NULL) {
      
      if($code){
        return Language::where('status',1)->where('code',$code)->pluck('name','id')->toArray();
      }else if($list){
        return Language::where('status',1)->pluck('name','id')->toArray();
      }else{
        return Language::where('status',1)->where('default_status',1)->select('name','id','code')->get();
      }
  }
}



if (!function_exists('getDefaultLanguageCount')) {
  function getDefaultLanguageCount($list=null,$code=NULL) {

        return Language::where('status',1)->where('default_status',1)->count();
   }
}

if (!function_exists('getCategoryNameByLangId')) {
  function getCategoryNameByLangId($cid=null,$lang_id=NULL) {

      return CategoryDescription::where('category_id',$cid)->where('lang_id',$lang_id)->first();
  }
}

if (!function_exists('getFaqNameByLangId')) {
  function getFaqNameByLangId($fid=null,$lang_id=NULL) {

      return FaqDescription::where('faq_id',$fid)->where('lang_id',$lang_id)->first();
  }
}

if (!function_exists('getQuestionByLangId')) {
  function getQuestionByLangId($qid=null,$lang_id=NULL) {

      return QuestionDescription::where('question_id',$qid)->where('lang_id',$lang_id)
                                      ->first();
  }
}

if (!function_exists('getOptiosOfQuestionByLangId')) {
  function getOptionsOfQuestionByLangId($qid=null,$lang_id=NULL) {

      return QuestionOption::where('question_id',$qid)->where('lang_id',$lang_id)
                                      ->get();
  }
}

if (!function_exists('countOptions')) {
  function countOptions($qid=null,$lang=['en-IN','hi-IN']) {

      return QuestionOption::where('question_id',$qid)
                            ->whereIn('lang_id',$lang)
                            ->count();
  }
}

if (!function_exists('getCorrectAnswer')) {
  function getCorrectAnswer($qid=null,$lang=NULL) {

      return QuestionOption::where('question_id',$qid)
                            ->where('lang_id',$lang)
                            ->where('is_correct',1)->first();
  }
}

if (!function_exists('getTournamentShedule')) {
  function getTournamentShedule($type=null) {

      return Tournament::sheduleTypeList($type);
  }
}

if (!function_exists('getWinningAmountType')) {
  function getWinningAmountType($type=null) {

      return Tournament::winAmtTypeList($type);
  }
}

if (!function_exists('getUserAppSetting')) {
  function getUserAppSetting($userid=null) {

      return UserAppSetting::findByUserId($userid);
  }
}

if (!function_exists('ifsccodeCheck')) {
    function ifsccodeCheck($ifsccode){
      $urls="https://ifsc.razorpay.com/$ifsccode";
        $headers = [
              'Accept: application/json',
          'Content-Type: application/json'
        ];
        $ch = curl_init ();
        curl_setopt($ch, CURLOPT_URL, $urls);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec ( $ch );
            curl_close ( $ch );
            if(!empty($result) && $result!='Not Found') { 
              $rst=json_decode($result);
            if(isset($rst->IFSC) && strtoupper($rst->IFSC)==strtoupper($ifsccode)) {
            $resArr['code']  = 0;
            $resArr['error'] = false;
            $resArr['msg']   = "IFSC code verified";  
            $resArr['data']  = $rst;
          }else{
            $resArr['code']  = 1;
            $resArr['error'] = true;
            $resArr['msg']   = "Invalid IFSC code"; 
          } 
        }else{
          $resArr['code']  = 1;
          $resArr['error'] = true;
          $resArr['msg']   = "Invalid IFSC code"; 
        }
      return $resArr;
    }
  }