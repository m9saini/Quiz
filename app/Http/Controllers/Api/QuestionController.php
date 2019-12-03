<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use Illuminate\Routing\Controller;
use App\Question;
use Modules\StaticPages\Entities\StaticPages;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class QuestionController extends BaseController
{
    
    public function __construct(Request $request,Question $question)
	{
		$this->Question = $question;
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
          /*  if(md5("MobileAppAPI")!=$request->headers->get('mobileappapi')) return $this->ValidateHeader();*/
            $question = Question::all()->whereNotIn('id',[1])->random(1);
            return $this->response->array(["question"=>$question,'time'=>Carbon::now(),'user_settings'=>getUserAppSetting()]);
        }
        catch (\Exception $e)
        {
            return $this->response->array(["message"=>$e->getMessage(),"status_code"=>500])->setStatusCode(500);
            //return $this->ValidateHeader();
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
