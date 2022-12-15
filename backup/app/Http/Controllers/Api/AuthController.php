<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use Validator;
use Log;
use File;
use Illuminate\Support\Facades\DB;
use Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    /*public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout', 'getLogout']]);
    }*/
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function signup(Request $request)
    {     
          $validator = Validator::make($request->all(),[
            'firstname' => 'required',
            'lastname'  => 'required',
            'email'      => 'string|email|unique:users|max:100',
            'mobile_no' => [
                    'required'
            ],
            'password'   => 'required'
         ]);

        if($validator->fails()){
           return response()->json([
            'status_code' => 400,
            'message'     => ($validator->errors()->first())
           ], 200);
           
        }
         $user = User::updateOrCreate([
            'email' => $request->email,
        ], 
        [ 
			'firstname'      => $request->firstname,
			'lastname'       => $request->lastname,
			'email'           => $request->email,
			'mobile_no'       => $request->mobile_no,
        ]);

        $user   = Auth::loginUsingId($user->id);
        
        // $msg91Response = $MSG91->sendSMS($otp,$request->mobile_no);
		$responce = [
			'status_code' =>200,
			'content'     =>(object) [
			'data'   =>$user,
			],
			'message'     =>'Signup successfully'
		];
         return response()->json($responce);
    }

  
    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
         $validator = Validator::make($request->all(),[
            'mobile_no'  => 'required|min:11|numeric',
            'password'   => 'required',
         ]);

        if($validator->fails()){
         return response()->json([
            'status_code' => 400,
            'content'     => (object) [
            ],
            'message'     => ($validator->errors()->first())
         ], 200);
        }
               
        if(Auth::attempt(['mobile_no' => request('mobile_no'), 'password' => request('password'), 'is_verified' =>'1','type' =>'user'])){    
          $user = Auth::user();
            $token  =  $user->createToken('MyApp')->accessToken;
             if ($request->has('device_id')) {
                $user->device_id = $request->device_id; 
             }
             if ($request->has('device_type')) {
                $user->device_type = $request->device_type; 
             }
            $user->save();
           
            if(empty($user->first_name)){
                    $user->first_name = '';
            }
            if(empty($user->last_name)){
                    $user->last_name = '';
            }
            if(empty($user->email)){
                    $user->email = '';
            }
            if(empty($user->mobile_no)){
                    $user->mobile_no = '';
            }
            if(empty($user->driving_licnce_no)){
                    $user->driving_licnce_no = '';
            }
            if(!empty($user->profile_image)){
              $user->profile_image = url('/public/uploads/profile').'/'.$user->profile_image;
            }else{
              $user->profile_image = '';
            }
            if(empty($user->company_name)){
                    $user->company_name = '';
            }
            if(!empty($user->pancard_image)){
               $user->pancard_image = url('/public/uploads/documentation').'/'.$user->pancard_image;
            }else{
                $user->pancard_image = '';
            }
            if(empty($user->pan_number)){
                    $user->pan_number = '';
            }
            if(empty($user->gst_number)){
                    $user->gst_number = '';
            }
            if(empty($user->state)){
                    $user->state = '';
            }
            if(empty($user->district)){
                    $user->district = '';
            }
            if(empty($user->city)){
                    $user->city = '';
            }
            if(empty($user->address)){
                    $user->address = '';
            }
            if(empty($user->house_no)){
                    $user->house_no = '';
            }
            if(empty($user->landmark)){
                    $user->landmark = '';
            }
            if(empty($user->area_name)){
                    $user->area_name = '';
            }
             if(empty($user->device_id)){
                    $user->device_id = '';
            }
            if(empty($user->device_type)){
                    $user->device_type = '';
            }

            if(empty($user->transporter_charge_percentage)){
                    $user->transporter_charge_percentage = '';
            }
             unset($user->transporter_charge_percentage);
              unset($user->otp);
              unset($user->transporter_id);
            $responce = [
                'status_code' =>200,
                'content'     =>(object) [
                'token'       =>$token,
                'user_data'   =>$user,
                ],
                'message'     =>'Login successfully'
            ];
            return response()->json($responce);     
        }else{
            $user = User::where('mobile_no',request('mobile_no'))
                ->where('is_verified','=','0');
            if($user->count() == 1){
                 $responce = [
                    'status_code' =>400,
                    'content'     =>(object) [
                    ],
                    'message'     =>'This Mobile Number is not varified'
                ];
                return response()->json($responce);  
            }else{
              $responce = [
                    'status_code' =>400,
                    'content'     =>(object) [
                    ],
                    'message'     =>'Mobile Number Or Password Incorrect'
                ];
                return response()->json($responce);  
            }
        }
    }
        
}
