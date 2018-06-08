<?php
namespace App\Modules\Api\Merchant;

use App\Http\Controllers\Controller;
use App\Models\MerchantStaff;
use App\Modules\Merchant\MerchantController;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Lcobucci\JWT\Parser;

class MerchantApiController extends Controller {
    public $systemLang;
    public $JsonData;
    public $StatusCode = 200;
    public $Code = 100;
    public $lastupdate;
    public $Date = '2018-01-27 12:00:11';
    public $AppVersion = '1.0';
    public function __construct(){
        
        $this->Date = setting('merchant_mobile_app_database_lastupdate');
        $this->AppVersion = setting('merchant_mobile_application_version');


        $this->middleware('auth:apiMerchant')->except(['login','callback','sendResetLinkEmail','register','getDatabase',
            'aboutUs','checkversion','DownloadApk']);


        $this->lastupdate = (object)[
            'Database'              => $this->Date,
            'Application'           => $this->AppVersion,
        ];
        $this->content = [];

        $this->JsonData = request()->all();

        if((isset($this->JsonData['lang'])) && (in_array($this->JsonData['lang'],['ar','en']))){
            $this->systemLang = $this->JsonData['lang'];
        }else{
            $this->systemLang = App::getLocale();
        }

        //$headerdata = file_get_contents("php://input");
        //$headerdata = request();
        /*
        if($this->isJson($headerdata) || ($headerdata == '') || ($headerdata=='""')) {
            if(strlen($headerdata) != '""')
                $this->JsonData = json_decode($headerdata,true);
            else
                $this->JsonData = array();
        } else
            return abort(404);
        */
    }

    public function login(Request $request){
        $RequestData = $request->only(['username','password','rememberme','merchant_id']);
        $validator = Validator::make($RequestData, [
            'username'          =>  'required|exists:merchant_staff,id',
            'password'          =>  'required',
            'merchant_id'       =>  'required|exists:merchants,id',
            'rememberme'        =>  'required|in:0,1'
        ]);

        if($validator->errors()->any()){
            return $this->ValidationError($validator,__('Validation Error'));
        }

        //TODO Token lifetime

        if(Auth('merchant_staff')->validate(['id' => $RequestData['username'], 'password' => $RequestData['password']])){
            $User = MerchantStaff::where('id',$RequestData['username'])->first();
            if($User->status !== 'active')
                return $this->setCode(102)->respondWithError(false,__('User account not activated'));
            if($User->merchant()->id != $RequestData['merchant_id'])
                return $this->setCode(102)->respondWithError(false,__('Wrong merchant id'));

            if($User->merchant()->status !== 'active')
                return $this->setCode(102)->respondWithError(false,__('Merchant account not activated'));

            /*
            if($User->merchant_staff_group->status !== 'active')
                return $this->setCode(102)->respondWithError(false,__('Employee group not activate'));
            */
            $client = new \GuzzleHttp\Client;
            try {
                $response = $client->post(getenv('APP_URL') . '/oauth/token', [
                    'form_params' => [
                        'client_id' => getenv('auth.client.merchant.id'),
                        // The secret generated when you ran: php artisan passport:install
                        'client_secret' => getenv('auth.client.merchant.secret'),
                        'grant_type' => 'password',
                        'username' => $RequestData['username'],
                        'password' => $RequestData['password'],
                        'scope' => '*',
                    ]
                ]);
                $auth = json_decode( (string) $response->getBody() );
                /*
                 * Must Change password
                 */
                if($User->must_change_password == 1){
                    $auth->must_change_password = true;
                }
                return $this->respondWithoutError($auth,'Successfully logged in');
            } catch (RequestException $e){
                return $this->setCode(102)->respondWithError(false,__('Couldn\'t generate token, try again later'));
            }
        } else {
            return $this->setCode(102)->respondWithError(false,__('Wrong username OR password'));
        };
    }

    public function logout(Request $request){
        $value = $request->bearerToken();
        $user = Auth::user();
        $id= (new Parser())->parse($value)->getHeader('jti');
        $user->tokens()->where('id','=',$id)->first()->revoke();
        $json = [
            'status' => true,
            'code' => 100,
            'msg' => __('Logged out'),
        ];
        return response()->json($json, '200');
    }

    public function checkuserStatus($user=null){
        $userobj = (($user) ? $user : (Auth::user()) ? Auth::user() : null);
        if(isset($userobj) && ($userobj->status == 'in-active'))
            return $this->respondWithError(false,__('Deactivated Account'));
        //TODO add Check for Merchant if its active
        if(isset($userobj) && ($userobj->merchant()->status == 'in-active'))
            return $this->respondWithError(false,__('Deactivated Merchant'));
    }

    function no_access(){
        return ['status'=>false,'msg'=> __('You don\'t have permission to preform this action')];
    }


    function headerdata($keys){
        return request()->only($keys);
        /*
        if(count($this->JsonData) == 0)
            return [];
        if(is_array($keys)) {
            $response = [];
            foreach ($keys as $key) {
                $response[$key] = array_key_exists($key,$this->JsonData) ? $this->JsonData[$key] : null;
            }
            request()->merge($response);
            return $response;
        } elseif (isset($keys)){
            $response = array_key_exists($keys,$this->JsonData)  ? [$keys=>$this->JsonData[$keys]] : null;
                request()->merge($response);
            return $response;
        } else {
            request()->merge($this->JsonData);
            return $this->JsonData;
        }
        */
    }


    function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }



    public function setStatusCode($StatusCode){
        $this->StatusCode = $StatusCode;
        return $this;
    }



    public function getStatusCode(){
        return $this->StatusCode;
    }

    public function setCode($code){
        $this->Code = $code;
        return $this;
    }

    public function getCode(){
        return $this->Code;
    }

    function ReturnMethod($condition,$truemsg,$falsemsg,$data=false){
        if($condition)
            return ['status'=>true,'msg'=>$truemsg,'data'=>$data];
        else
            return ['status'=>false,'msg'=>$falsemsg,'data'=>$data];
    }

    public function respondSuccess($data,$message = 'Success'){
        return $this->setStatusCode(200)->setCode(100)->respondWithoutError($data,$message);
    }

    public function respondCreated($data,$message = 'Row has been created'){
        return $this->setStatusCode(200)->setCode(100)->respondWithoutError($data,$message);
    }

    public function respondNotFound($data,$message = 'Not Found!'){
        return $this->setStatusCode(200)->setCode(101)->respondWithError($data,$message);
    }

    public function respond($data,$headers=[]){
        $data['version'] = $this->lastupdate;
        return response()->json($data,$this->getStatusCode(),$headers);
    }

    public function respondWithoutError($data,$message){
        if(is_array($data)){
            $data['version'] = $this->lastupdate;
        } else if(is_object($data)) {
            $data->version = $this->lastupdate;
        } else {
            $data = array_merge([$data],[
                'version'=> $this->lastupdate,
            ]);
        }
        return response()->json([
            'status' => true,
            'msg' => $message,
            'code' => $this->getCode(),
            'data'=>$data
        ],$this->getStatusCode());
    }

    public function respondWithError($data,$message){
        if(is_array($data)){
            $data['version'] = $this->lastupdate;
        } else if(is_object($data)) {
            $data->version = $this->lastupdate;
        } else {
            $data = array_merge([$data],[
                'version'=> $this->lastupdate,
            ]);
        }
        return response()->json([
            'status' => false,
            'msg' => $message,
            'code' => $this->getCode(),
            'data'=>$data
        ],$this->getStatusCode());
    }

    public function permissions($permission=false){
        $permissions = \Illuminate\Support\Facades\File::getRequire('../app/Modules/Merchant/Permissions.php');
        return $permission ? isset($permissions[$permission]) ? $permissions[$permission] : false : $permissions;
    }

    public function permissionsNames($permission=false,$reverse=false){
        $permissions = $this->permissions();
        $data = [];
        foreach($permissions as $key=>$val){
            $data = array_merge($data,[$key=>__(ucfirst(str_replace('-',' ',$key)))]);
        }
        if($reverse)
            return array_search($permission,$data);
        else
            return $data ? isset($data[$permission]) ? $data[$permission] : false : $data;
    }

    public function ValidationError($validation,$message){
        $errorArray = $validation->errors()->messages();

        $data = array_column(array_map(function($key,$val) {
            return ['key'=>$key,'val'=>implode('|',$val)];
        },array_keys($errorArray),$errorArray),'val','key');

        $data['version'] = $this->lastupdate;
        //$data['msgs'] = implode("\n",array_flatten($errorArray));

        return $this->setCode(103)->respondWithError($data,implode("\n",array_flatten($errorArray)));
    }

    public function DownloadApk(Request $request)
    {
        //$path = storage_path('app/public/latest-app.apk');

        //return response()->file($path);
        return response()->download(storage_path('app/public/latest-app.apk'));
    }

}