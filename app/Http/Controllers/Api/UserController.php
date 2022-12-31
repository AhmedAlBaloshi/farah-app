<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\User;
use Illuminate\Support\Facades\Auth;
use APIHelper;
use App\PasswordReset;
use App\Notifications\PasswordResetRequest;
use Carbon\Carbon;
use Hash;
use DB;
use Storage;
use Image;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends BaseController
{

    public function getCustomers()
    {
        $customers = User::where('role_id', 3)->get();
        if ($customers) {
            return response()->json([
                'success' => 1,
                'customers' => $customers
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed to load customers from database'
        ], 404);
    }

    public function getSellers()
    {
        $sellers = User::where('role_id', 4)->get();
        if ($sellers) {
            return response()->json([
                'success' => 1,
                'sellers' => $sellers
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed to load sellers from database'
        ], 404);
    }

    public function getStaffs()
    {
        $staffs = User::where('role_id', 2)->get();
        if ($staffs) {
            return response()->json([
                'success' => 1,
                'staffs' => $staffs
            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed to load staffs from database'
        ], 404);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|min:3',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($request->mobile_no) {
            $validator = Validator::make($request->all(), [
                'mobile_no'    => 'required|digit:8',
            ]);
        }
        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()
            ], 400);
        }


        $user = User::add($request->all());

        if ($user) {
            return response()->json([
                'success' => 1,
                'message' => 'User added successfully',
                "user_id" => $user->id

            ], 200);
        }
        return response()->json([
            'success' => 0,
            'message' => 'Failed to add user'
        ], 404);
    }

    public function update(Request $request, $id)
    {
        if ($request->mobile_no) {
            $validator = Validator::make($request->all(), [
                'mobile_no'    => 'required|digit:8',
            ]);
        }

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()
            ], 400);
        }

        $user =  User::updateRecords($id, $request->all());
        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Failed, User not found'
            ], 404);
        }
        return response()->json([
            'success' => 1,
            'message' => 'User updated successfully',
            "user_id" => $id
        ], 200);
    }

    /**
     * Logout user (Revoke the token)
     * Auther : Kishan Busa 
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $user  = $request->user();
        $token = $request->user()->token();
        $token->revoke();
        return $this->send_response([], 'User logout successfully.');
    }

    /** 
     * Send OTP API 
     * Auther : Kishan Busa 
     * @return \Illuminate\Http\Response 
     */
    public function send_otp(Request $request)
    {

        $customMessages = [
            'mobile_no' => 'mobile number id required',
        ];

        $validator = Validator::make($request->all(), [
            'mobile_no' => 'required|min:10|numeric',
        ], $customMessages);

        if ($validator->fails()) {
            return $this->send_error($validator->errors()->first());
        }

        $result   = DB::select("exec user_crud 'select_single', 0, NULL, NULL, NULL, NULL, '" . $request->mobile_no . "';");
        $user     = reset($result);

        if (isset($request->mobile_no) && empty($user)) {

            return $this->send_error('Your mobile number not register');
        } else {

            $otp           = APIHelper::unique_code(4);
            $message       = "Your OTP  is " . $otp;
            $msg_response  = APIHelper::send_sms($request->mobile_no, $message);
            $otp_validity  = Carbon::now()->addMinutes(10);
            $updated_at    = date('Y-m-d H:i:s');

            $update = DB::update("exec user_crud 'update_otp', NULL, NULL, NULL, NULL, NULL, '" . $request->mobile_no . "', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '" . $updated_at . "', NULL, NULL, NULL, NULL, NULL, '" . $otp . "', '" . $otp_validity . "';");

            if ($msg_response['error']) {

                return $this->send_error($msg_response['message']);
            } else {

                $success['otp'] = $otp;
                return $this->send_response($success, 'Your OTP is created.');
            }
        }
        return response()->json($responce);
    }
    /**
     * Verify OTP API.
     * Auther : Kishan Busa 
     * @return Response
     */
    public function verify_otp(Request $request)
    {
        $customMessages = [
            'mobile_no.required' => 'The mobile no field is required.',
            'otp.required'       => 'The otp field is required',
        ];

        $validator = Validator::make($request->all(), [
            'mobile_no' => 'required|min:10|numeric',
            'otp'       => 'required',
        ], $customMessages);

        if ($validator->fails()) {
            return $this->send_error($validator->errors()->first());
        }

        $entered_otp = $request->otp;
        $mobile_no   = $request->mobile_no;

        $result   = DB::select("exec user_crud 'select_single', 0, NULL, NULL, NULL, NULL, '" . $request->mobile_no . "';");
        $user     = reset($result);

        if (empty($user)) {

            return $this->send_error('You are not register mobile mumber.');
        } else {

            $otp = $user->otp;
            $current_time      = Carbon::now();
            $otp_validity_date = $user->otp_validity_date;

            if ($otp_validity_date < $current_time) {

                return $this->send_error('You otp is expired');
            }

            if ($otp == $entered_otp) {
                $updated_at    = date('Y-m-d H:i:s');
                $is_verified   = 1;

                $update = DB::update("exec user_crud 'update_otp', NULL, NULL, NULL, NULL, NULL, '" . $request->mobile_no . "', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '" . $updated_at . "', NULL, NULL, NULL, NULL, NULL, NULL, NULL, $is_verified;");

                $user->profile_image = '';

                if (!empty($user->profile_image)) {
                    $user->profile_image  = storage_path('uploads/users/' . $user->profile_image);
                }

                $user  = User::where('mobile_no', request('mobile_no'))->first();
                unset($user->otp);
                $success['token'] =  $user->createToken('MyApp')->accessToken;
                $success['user']  =  $user;

                return $this->send_response($success, 'Your Number is Verified.');
            } else {

                return $this->send_error('OTP does not match.');
            }
        }
    }
    /** 
     * Forgot password API 
     * Auther : Kishan Busa 
     * @return \Illuminate\Http\Response 
     */
    public function forgot_password(Request $request)
    {

        $params = $request->all();

        $customMessages = [
            "login_type.required" => "Email or Mobile number is required",
            "otp.required"        => "OTP is required",
            "mobile_no.required"  => "Mobile number is required",
            "email.required"      => "Email is required",
            "password.required"   => "Passworrd is required",
        ];

        $validator = Validator::make($params, [
            'login_type'    => 'required',
            'mobile_no'     => 'required_if:login_type,==,mobile_no',
            'email'         => 'required_if:login_type,==,email',
            'otp'           => 'required_if:login_type,==,mobile_no',
            'password'      => 'required_if:login_type,==,mobile_no|same:password',
            'password_confirmation' => 'required_if:login_type,==,mobile_no|same:password',
        ], $customMessages);

        if ($validator->fails()) {

            return $this->send_error($validator->errors()->first());
        }

        if ($request->login_type == 'email') {

            //$result      = DB::select("exec user_crud 'select_single', 0, NULL, NULL, NULL, '".$request->email."';");
            //$user        = reset($result);
            $user        = User::where('email', $request->email)->first();
            //dd($user);

            $created_at  = date('Y-m-d H:i:s');
            $updated_at  = date('Y-m-d H:i:s');

            if (!$user) {

                return $this->send_error('We cant find a user with that e-mail address.');
            }

            $result       = DB::update("exec password_reset_crud 'insert_update', '" . $request->email . "', '" . str_random(60) . "', '" . $created_at . "', '" . $updated_at . "';");

            $result       = DB::select("exec password_reset_crud 'select_single', '" . $request->email . "';");
            $passwordReset   = reset($result);

            if ($user && $passwordReset) {

                $user->notify(
                    new PasswordResetRequest($passwordReset->token)
                );

                return $this->send_response([], 'We have e-mailed your password reset link!');
            }
        } else if ($request->login_type == 'mobile_no') {

            $request_otp = $params['otp'];

            $result   = DB::select("exec user_crud 'select_single', 0, NULL, NULL, NULL, NULL, '" . $request->mobile_no . "';");
            $user     = reset($result);

            $OTP      = isset($user->otp) ? $user->otp : '';

            if (empty($user)) {

                return $this->send_error('These credentials do not match our records.');
            } else {

                $current_time      = Carbon::now();
                $otp_validity_date = $user->otp_validity_date;

                if ($otp_validity_date < $current_time) {

                    return $this->send_error('You otp is expired');
                }

                if ($OTP === $request_otp) {

                    $user_id    = $user->id;
                    $password   = Hash::make($request['password']);
                    $update     = DB::update("exec user_crud 'update', $user_id, NULL, '" . $password . "', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '" . $updated_at . "', NULL, NULL, NULL, NULL, NULL, NULL, NULL;");

                    return $this->send_response([], 'Passworrd Update successfully');
                } else {

                    return $this->send_error('You otp does not matches');
                }
            }
        }
    }

    /** 
     * Language List API 
     * Auther : Kishan Busa 
     * @return \Illuminate\Http\Response 
     */
    public function language_list()
    {

        $result = DB::select("exec language_crud 'active_record'");
        if (!$result) {
            return $this->send_error('Language list not found.');
        }

        return $this->send_response($result, 'Language list get successfully');
    }

    /** 
     * Language data  API 
     * Auther : Kishan Busa 
     * @return \Illuminate\Http\Response 
     */
    public function language_data(Request $request)
    {

        $customMessages = [
            'language_id' => 'Language id is required',
        ];

        $validator = Validator::make($request->all(), [
            'language_id' => 'required',
        ], $customMessages);

        if ($validator->fails()) {
            return $this->send_error($validator->errors()->first());
        }

        $user_id = Auth::id();

        $result = DB::select("exec language_data_crud 'select_language_data', NULL, $request->language_id, NULL, NULL, NULL, NULL, NULL, $user_id, 'App'");

        if (!$result) {

            return $this->send_error('Language data not found.');
        }

        return $this->send_response($result, 'Language data get successfully');
    }
    /** 
     * User profile Detail API 
     * Auther : Kishan Busa 
     * @return \Illuminate\Http\Response 
     */
    public function profile(Request $request)
    {

        $user = Auth::user();

        if (!empty($user->profile_image)) {
            $user->profile_image = asset('storage/uploads/users/' . $user->profile_image);
        }

        unset($user->last_login);
        unset($user->email_verified_at);
        unset($user->company_id);
        unset($user->branch_id);
        unset($user->application_id);
        unset($user->otp);
        unset($user->otp_validity_date);
        unset($user->is_verified);
        unset($user->is_user_action);
        unset($user->filter_option);
        unset($user->smooth_tracking);
        unset($user->immobilization);
        unset($user->app_notification);
        unset($user->show_company_logo);
        unset($user->is_change_password);
        unset($user->map_id);
        unset($user->created_by);

        $select_user_company = DB::select("exec user_crud 'select_user_company1', $user->id");

        $user_company = count($select_user_company) > 0 ? array_column($select_user_company, 'company_id') : [];

        $company_id =  implode(',', $user_company);
        // $branch             = DB::select("exec branch_crud 'company_branch', NULL, '".$company_id."';");
        if (Auth::user()->roll_id == 0) {
            // $company_id = isset($request->company_id) ? implode(',', $request->company_id) : 0;
            $branch = DB::select("exec branch_crud 'company_branch', NULL, '" . $company_id . "';");
        } else {
            $branch = DB::select("exec branch_crud 'user_wise_branch', $user->id, '" . $company_id . "';");
        }
        $user->company = DB::select("exec company_by_user $user->id;");
        $user->branch = $branch;

        $success['user']  =  $user;

        return $this->send_response($success, 'User profile details successfully.');
    }

    /** 
     * User profile Detail API 
     * Auther : Kishan Busa 
     * @return \Illuminate\Http\Response 
     */
    public function update_profile(Request $request)
    {

        $customMessages = [
            'profile_image' => 'profile image is required',
        ];

        $user_id     = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'profile_image' => 'mimes:jpeg,jpg',
            'mobile_no'     => 'unique:users,mobile_no,' . $user_id,
            'email'         => 'email|unique:users,email,' . $user_id,
        ], $customMessages);

        if ($validator->fails()) {
            return $this->send_error($validator->errors()->first());
        }

        $user        = Auth::user();
        $updated_at  = date('Y-m-d H:i:s');

        /*if ($request->has('username')) {
            $user->username = $request->username;
        } */

        if ($request->has('mobile_no')) {
            $user->mobile_no = $request->mobile_no;
        }

        /*if ($request->has('email')) {
            $user->email = $request->email;
        } */

        $profile_image        = isset($user->profile_image) ? $user->profile_image : '';
        if ($request->hasFile('profile_image')) {

            $filename = storage_path('uploads/users/' . $user->profile_image);

            if (File::exists($filename)) {
                File::delete($filename);
            }

            $image         = $request->file('profile_image');
            $profile_image = time() . '.' . $image->getClientOriginalExtension();

            $img = Image::make($image->getRealPath());
            $img->stream(); // <-- Key point

            Storage::disk('public')->put('uploads/users/' . $profile_image, $img, 'public');
        }

        if (!empty($user->profile_image)) {
            $user->profile_image = asset('storage/uploads/users/' . $user->profile_image);
        }

        $result = DB::update("exec api_user_crud 'update', $user_id, 'NULL', 'NULL', NULL, '$user->email', '$user->mobile_no', NULL, '$profile_image', NULL, '" . $updated_at . "';");

        unset($user->last_login);
        unset($user->email_verified_at);
        unset($user->company_id);
        unset($user->branch_id);
        unset($user->application_id);
        unset($user->otp);
        unset($user->otp_validity_date);
        unset($user->is_verified);
        unset($user->is_user_action);
        unset($user->filter_option);
        unset($user->smooth_tracking);
        unset($user->immobilization);
        unset($user->app_notification);
        unset($user->show_company_logo);
        unset($user->is_change_password);
        unset($user->map_id);
        unset($user->created_by);

        $success['user'] = $user;

        return $this->send_response($success, 'User profile details successfully.');
    }
    /** 
     * User Update Password API 
     * Auther : Kishan Busa 
     * @return \Illuminate\Http\Response 
     */
    public function reset_password(Request $request)
    {
        if (Auth::Check()) {

            $customMessages = [
                'current_password.required' => 'Please enter current password',
                'password.required'         => 'Please enter password',
                'password_confirmation'     => 'Your New password and Confirm password must be same'
            ];

            $validator = Validator::make($request->all(), [
                'current_password'      => 'required',
                'password'              => 'required|same:password',
                'password_confirmation' => 'required|same:password',
            ], $customMessages);

            if ($validator->fails()) {
                return $this->send_error($validator->errors()->first());
            }

            $current_password = Auth::User()->password;
            if (Hash::check($request->current_password, $current_password)) {

                $user_id    = Auth::User()->id;
                $password   = Hash::make($request->password);
                $updated_at = date('Y-m-d H:i:s');

                $result     = DB::update("exec user_crud 'update', $user_id, NULL, '" . $password . "' , NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '" . $updated_at . "';");


                return $this->send_response([], 'Passworrd Update successfully');
            } else {

                return $this->send_error("You current password does not matches");
            }
        } else {
            return $this->send_error("Not Auth User");
        }
    }
    /** 
     * User Setting List API 
     * Auther : Kishan Busa 
     * @return \Illuminate\Http\Response 
     */
    public function setting_list(Request $request)
    {

        $user = Auth::user();

        $success['language_id']      = $user->language_id;
        $success['app_notification'] = $user->app_notification;

        return $this->send_response($success, 'Setting list successfully');
    }
    /** 
     * User Setting Update API 
     * Auther : Kishan Busa 
     * @return \Illuminate\Http\Response 
     */
    public function setting_update(Request $request)
    {

        $user          = Auth::user();
        $updated_at    = date('Y-m-d H:i:s');

        if ($request->has('language_id')) {
            $user->language_id = $request->language_id;
        }

        if ($request->has('app_notification')) {
            $user->app_notification = $request->app_notification;
        }

        $result = DB::update("exec user_crud 'update', $user->id, NULL, NULL, NULL, NULL, NULL, NULL, '$user->language_id', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$user->app_notification', NULL, NULL, NULL, NULL, '" . $updated_at . "';");

        return $this->send_response([], 'Setting update successfully');
    }

    public function vehicle_list(Request $request)
    {

        $params = $request->all();

        $col         = ["id", "vehicle_no", "email", "mobile_no"];
        $page_no     = isset($params['start']) ? $params['start'] : 1;
        $page_size   = isset($params->length) ? $params->length : 10;
        $sort_by     = isset($params->order) && isset($params->order[0]['column']) ? $col[$params->order[0]['column']] : $col[0];
        $sort_order  = isset($params->order) && isset($params->order[0]['column']) ? $params->order[0]['dir'] : 'ASC';
        $search_text        = !empty($params['search_text']) ? "'" . $params['search_text'] . "'" : 'NULL';
        $company_id         = !empty($params['company_id']) ? "'" . $params['company_id'] . "'" : 'NULL';
        $branch_id          = !empty($params['branch_id']) ? "'" . $params['branch_id'] . "'" : 'NULL';
        $vehicle_type_id    = !empty($params['vehicle_type_id']) ? "'" . $params['vehicle_type_id'] . "'" : 'NULL';
        $vehicle_brand_id   = !empty($params['vehicle_brand_id']) ? "'" . $params['vehicle_brand_id'] . "'" : 'NULL';
        $vehicle_model_id   = !empty($params['vehicle_model_id']) ? "'" . $params['vehicle_model_id'] . "'" : 'NULL';

        $special_feature    = isset($params['special_feature']) ? $params['special_feature'] : 'NULL';
        $is_active          = isset($params['is_active']) ? $params['is_active'] : 'NULL';
        $alert_group_id     = isset($params['alert_group_id']) ? $params['alert_group_id'] : 'NULL';

        $all_user            = Auth::id();
        //$user_id  = 2018;

        //$all_user = CustomHelper::get_downline_user($user_id);

        //dd($all_user);

        $result = DB::select("exec vehicle_select 0, $page_no, $page_size, '$sort_by' , '$sort_order', $search_text, $company_id, $branch_id, $vehicle_type_id, $vehicle_brand_id, $vehicle_model_id, $special_feature, $is_active, $alert_group_id, '$all_user';");

        if (!$result) {

            return $this->send_error('Vehicle data not found.');
        }

        return $this->send_response($result, 'Vehicle List get successfully');
    }

    public function company_list(Request $request)
    {

        $user_id = Auth::id();

        $result = DB::select("exec company_by_user $user_id;");

        if (!$result) {

            return $this->send_error('Conmapny data not found.');
        }

        return $this->send_response($result, 'Vehicle List get successfully');
    }

    public function branch_list(Request $request)
    {

        $company_id = isset($request->company_id) ? $request->company_id : 0;
        $user_id = Auth::user()->id;
        if (Auth::user()->roll_id == 0) {
            $result = DB::select("exec branch_crud 'company_branch', NULL, '" . $company_id . "';");
        } else {
            $result = DB::select("exec branch_crud 'user_wise_branch', $user_id, '" . $company_id . "';");
        }

        return $this->send_response($result, 'Branch List get successfully');
    }

    public function user_list(Request $request)
    {

        $user_id = Auth::id();

        $result = DB::select("exec api_user_list $user_id;");

        if (!$result) {

            return $this->send_error('User Not Found!');
        }

        $data = [];

        foreach ($result as $key => $user) {

            $profile_image = '';
            if (!empty($user->profile_image)) {

                $filename = storage_path('uploads/users/' . $user->profile_image);
                if (File::exists($filename)) {
                    $profile_image = asset('storage/uploads/users/' . $user->profile_image);
                }
            }

            $data[$user->app_user][] = [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'mobile_no' => $user->mobile_no,
                'profile_image' => $profile_image,
            ];
        }

        return $this->send_response($data, 'User List get successfully');
    }

    public function user_list_by_type(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'app_user' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->send_error($validator->errors()->first());
        }

        $start = isset($request->start) ? $request->start : 1;
        $offset = isset($request->offset) ? $request->offset : 10;

        $user_id = Auth::id();

        $user = DB::select("exec api_all_user $user_id, $request->app_user;");
        $result = DB::select("exec api_user_list $user_id, $start, $offset, '', '" . $request->app_user . "';");
        $total_user = reset($user);
        if (!$result) {

            return $this->send_error('User Not Found!');
        }

        $data = [];

        foreach ($result as $key => $user) {

            $profile_image = '';
            if (!empty($user->profile_image)) {

                $filename = storage_path('uploads/users/' . $user->profile_image);
                if (File::exists($filename)) {
                    $profile_image = asset('storage/uploads/users/' . $user->profile_image);
                }
            }

            $data['user'][] = [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'mobile_no' => $user->mobile_no,
                'total_vehicle' => $user->total_vehicle,
                'profile_image' => $profile_image,
            ];
        }
        $data['total_user'] = $total_user->total_user;

        return $this->send_response($data, 'User List get successfully');
    }

    /** 
     * User profile Detail API 
     * Auther : Kishan Busa 
     * @return \Illuminate\Http\Response 
     */
    public function get_user_details(Request $request)
    {

        $user = Auth::user();

        $params = $request->all();

        $customMessages = [
            "user_id.required" => "User id is required"
        ];

        $validator = Validator::make($params, [
            'user_id'    => 'required'
        ], $customMessages);

        if ($validator->fails()) {
            return $this->send_error($validator->errors()->first());
        }

        $user = DB::select("exec user_crud 'select_single', '" . $request->user_id . "'");

        $user = reset($user);

        if (empty($user)) {
            return $this->send_error('User not found!');
        }

        if (!empty($user->profile_image)) {
            $filename = storage_path('uploads/users/' . $user->profile_image);
            if (File::exists($filename)) {
                $user->profile_image = asset('storage/uploads/users/' . $user->profile_image);
            }
        }

        $id = $user->id;
        $result = [];
        if ($user->roll_id == 0) {
            $company_user = DB::select("exec company_by_user $user->id;");
            if (count($company_user) > 0) {
                $company_id = implode(',', array_column($company_user, 'id')) . ',';
                if (!empty($company_id)) {
                    $branch_user = DB::select("exec api_branch_by_vehicle '$company_id';");
                    $branch_id = implode(',', array_column($branch_user, 'id')) . ',';
                    if (!empty($branch_id)) {
                        $result = DB::select("exec api_vehicles 'details', $user->id, 0, '', '$company_id', '$branch_id'");
                    }
                }
            }
        } else {
            $select_user_vehicle = DB::select("exec user_crud 'select_user_vehicle', $id");
            $result         = count($select_user_vehicle) > 0 ? $select_user_vehicle : [];
        }

        $select_user_company = DB::select("exec user_crud 'select_user_company', $id");
        $user_company        = count($select_user_company) > 0 ? $select_user_company : [];

        $select_user_branch  = DB::select("exec user_crud 'select_user_branch', $id");
        $user_branch         = count($select_user_branch) > 0 ? $select_user_branch : [];


        $user->vehicle = $result;
        $user->selected_company = $user_company;
        $user->selected_branch = $user_branch;

        unset($user->last_login);
        unset($user->email_verified_at);
        unset($user->company_id);
        unset($user->branch_id);
        unset($user->application_id);
        unset($user->otp);
        unset($user->otp_validity_date);
        unset($user->is_verified);
        unset($user->is_user_action);
        unset($user->filter_option);
        unset($user->smooth_tracking);
        unset($user->immobilization);
        unset($user->app_notification);
        unset($user->show_company_logo);
        unset($user->is_change_password);
        unset($user->map_id);
        unset($user->created_by);
        unset($user->password);
        unset($user->roll_id);
        unset($user->is_active);
        unset($user->language_id);
        unset($user->timezone);
        unset($user->date_format);
        unset($user->time_format);
        unset($user->vehicle_status_chart);

        return $this->send_response($user, 'User profile');
    }

    /** 
     * User Add API 
     * Auther : Nilesh Bavliya 
     * @return \Illuminate\Http\Response 
     */

    public function add_user(Request $request)
    {
        $params = $request->all();

        $customMessages = [
            'username.unique' => 'User name already taken!',
        ];

        $validation = 'required|unique:users';
        if (!empty($request->user_id)) {
            $validation = 'required';
            $validation_arr =  [
                'email'    => $validation,
                'mobile_no' => $validation,
            ];
        } else {
            $validation = 'required';
            $validation_arr =  [
                'username' => $validation,
                'username' => $validation,
                'password' => 'min:6|required',
                'email'    => $validation,
                'mobile_no' => $validation,
            ];
        }

        $validator = Validator::make($params, $validation_arr, $customMessages);

        if ($validator->fails()) {
            return $this->send_error($validator->errors()->first());
        }

        $user_name      = isset($params['username']) ? $params['username'] : '';
        $mobile_no      = isset($params['mobile_no']) ? $params['mobile_no'] : '';
        $email          = isset($params['email']) ? $params['email'] : '';
        $password       = isset($params['password']) ? bcrypt($params['password']) : '';
        $user_group     = isset($params['roll_id']) ? $params['roll_id'] : 0;
        $company_id     = !empty($params['company_id']) ? $params['company_id'] : '';
        $branch_id      = !empty($params['branch_id']) ? $params['branch_id'] : '';
        $vehicle_id     = !empty($params['vehicle_id']) ? $params['vehicle_id'] : '';
        $company_name   = !empty($params['company_name']) ? $params['company_name'] : '';
        $branch_name    = !empty($params['branch_name']) ? $params['branch_name'] : '';
        $is_active      = 1;
        $language_id    = 1;
        $application_id = isset($params['application_id']) ?  count($params['application_id']) > 0 ? implode(',', $params['application_id']) . ',' : '' : '';
        $timezone       = '';
        $app_user       = isset($params['app_user']) ? $params['app_user'] : '';
        $user_id        = isset($params['user_id']) ? $params['user_id'] : '';
        $created_at     = date('Y-m-d H:i:s');
        $updated_at     = date('Y-m-d H:i:s');
        $created_by     = Auth::id();

        $profile_image = '';

        $ss = "exec api_user_crud 'update', $user_id, 'NULL', NULL, NULL, '$email', '$mobile_no', NULL, NULL, NULL, '" . $updated_at . "', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'NULL';";

        if ($request->hasFile('profile_image')) {

            // $filename = storage_path('uploads/users/' . $hidden_profile_image);

            // if (File::exists($filename)) {
            //     File::delete($filename);
            // }

            $image         = $request->file('profile_image');
            $profile_image = time() . '.' . $image->getClientOriginalExtension();

            $img = Image::make($image->getRealPath());
            $img->stream(); // <-- Key point

            Storage::disk('public')->put('uploads/users/' . $profile_image, $img, 'public');

            $ss = "exec api_user_crud 'update', $user_id, 'NULL', NULL, NULL, '$email', '$mobile_no', NULL, NULL, NULL, '" . $updated_at . "', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '" . $profile_image . "';";
        }

        if (!empty($user_id)) {
            $result = DB::update($ss);
            $msg    = 'User updated successfully!';
        } else {
            $result = DB::update("exec api_user_crud 'insert', 0, '$user_name', '$password', $user_group, '$email', '$mobile_no', '$is_active', $language_id, '" . $created_at . "', '" . $updated_at . "', '" . $company_id . "', '" . $branch_id . "', '" . $vehicle_id . "', '" . $application_id . "', $created_by, '" . $app_user . "', '" . $company_name . "', '" . $branch_name . "', '" . $profile_image . "';");
            $msg    = 'User added successfully!';
        }

        return $this->send_response((object) [], $msg);
    }

    /** 
     * GPS Device List API 
     * Auther : Nilesh Bavliya 
     * @return \Illuminate\Http\Response 
     */

    public function gps_device_list(Request $request)
    {
        $params = $request->all();

        $col         = ["id", "device_name"];
        $page_no     = isset($params->start) ? $params->start : 1;
        $page_size   = isset($params->length) ? $params->length : 1000;
        $sort_order  = 'DESC';
        $search_text = !empty($params['search_text']) ? "'" . $params['search_text'] . "'" : 'NULL';
        $company_id  = !empty($params['company_id']) ? "'" . $params['company_id'] . "'" : 'NULL';
        $branch_id   = !empty($params['branch_id']) ? "'" . $params['branch_id'] . "'" : 'NULL';
        $device_brand_id = !empty($params['device_brand_id']) ? "'" . $params['device_brand_id'] . "'" : 'NULL';
        $device_model_id = !empty($params['device_model_id']) ? "'" . $params['device_model_id'] . "'" : 'NULL';
        $sim_provider_id = !empty($params['sim_provider_id']) ? "'" . $params['sim_provider_id'] . "'" : 'NULL';

        $user_id  = Auth::id();

        $result = DB::select("exec gps_device_select 0, $page_no, $page_size, '' , '$sort_order', $search_text, $company_id, $branch_id, $device_brand_id, $device_model_id, $sim_provider_id, $user_id;");

        return $this->send_response($result, '');
    }

    public function application_list(Request $request)
    {

        $result   = DB::select("exec application_crud 'active_record', 0");

        return $this->send_response($result, '');
    }

    public function roll_list(Request $request)
    {

        $result  = DB::select("exec role_select 0");

        return $this->send_response($result, '');
    }

    /** 
     * Add GPS Device API 
     * Auther : Nilesh Bavliya 
     * @return \Illuminate\Http\Response 
     */

    public function add_company_branch(Request $request)
    {
        $params = $request->all();

        $validator = Validator::make($params, [
            'company_name'         =>  'required_if:company_id,""',
            'company_id'       =>  'required_if:company_name,""',
            'branch_name'         =>  'required_if:branch_id,""',
            'branch_id'       =>  'required_if:branch_name,""',
            // 'company_name'       => 'required',
            // 'company_id'  => 'required|numeric',
            // 'branch_name'     => 'required',
            // 'branch_id'   => 'required|numeric'

        ]);

        if ($validator->fails()) {
            return $this->send_error($validator->errors()->first());
        }

        $company_name           = !empty($params['company_name']) ? $params['company_name'] : '';
        $branch_name           = !empty($params['branch_name']) ? $params['branch_name'] : '';

        $company_id = isset($params['company_id']) ? $params['company_id'] : '';
        $branch_id  = isset($params['branch_id']) ? $params['branch_id'] : '';

        $created_at             = date('Y-m-d H:i:s');
        $updated_at             = date('Y-m-d H:i:s');
        $created_by             = Auth::user()->id;

        $result = DB::update("exec api_add_company_wise_vehicle $created_by, $company_id, $branch_id, '$company_name', '$branch_name', '" . $created_at . "', '" . $updated_at . "';");

        $msg = 'GPS Device added successfully!';

        return $this->send_response($result, 'Vehicle');
    }

    /** 
     * User Add API 
     * Auther : Nilesh Bavliya 
     * @return \Illuminate\Http\Response 
     */

    public function delete_user(Request $request)
    {
        $params = $request->all();

        $customMessages = [
            'user_id.required' => 'User id required!',
        ];

        $validator = Validator::make($params, [
            'user_id' => 'required',
        ], $customMessages);

        if ($validator->fails()) {
            return $this->send_error($validator->errors()->first());
        }

        $user_id = $request->user_id;

        $result = DB::update("exec user_crud 'delete', $user_id");

        $msg = 'User deleted successfully!';

        return $this->send_response((object) [], $msg);
    }

    /** 
     * Add User Vehicle API 
     * Auther : Nilesh Bavliya 
     * @return \Illuminate\Http\Response 
     */

    public function add_user_vehicle(Request $request)
    {
        $params = $request->all();

        $validator = Validator::make($params, [
            'vehicle_id' => 'required',
            'action' => 'required',
            'user_id' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->send_error($validator->errors()->first());
        }

        $vehicle_id     = !empty($params['vehicle_id']) ? $params['vehicle_id'] : '';
        $user_id        = isset($params['user_id']) ? $params['user_id'] : '';
        $action        = isset($params['action']) ? $params['action'] : '';
        $created_at     = date('Y-m-d H:i:s');
        $updated_at     = date('Y-m-d H:i:s');
        $created_by     = Auth::id();

        if ($action == 'insert') {
            $result = DB::update("exec user_vehicle_add_delete 'insert', $user_id, '" . $created_at . "', '" . $updated_at . "', $created_by, '" . $vehicle_id . "';");
            $msg    = 'Vehicle added successfully!';
        } else {
            $result = DB::update("exec user_vehicle_add_delete 'delete', $user_id, NULL, NULL, NULL, '" . $vehicle_id . "';");
            $msg    = 'Vehicle deleted successfully!';
        }

        return $this->send_response((object) [], $msg);
    }

    /** 
     * Change Language API 
     * Auther : Nilesh Bavliya 
     * @return \Illuminate\Http\Response 
     */

    public function change_language(Request $request)
    {
        $params = $request->all();

        $validator = Validator::make($params, [
            'language_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->send_error($validator->errors()->first());
        }

        $language_id = !empty($params['language_id']) ? $params['language_id'] : '';
        $updated_at  = date('Y-m-d H:i:s');
        $user_id  = Auth::id();

        DB::update("exec api_change_language $user_id, $language_id, '" . $updated_at . "';");
        $msg = 'Language set successfully!';

        return $this->send_response((object) [], $msg);
    }

    /** 
     * Change Password API 
     * Auther : Nilesh Bavliya 
     * @return \Illuminate\Http\Response 
     */

    public function change_password(Request $request)
    {
        $params = $request->all();

        $validator = Validator::make($params, [
            'user_id' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->send_error($validator->errors()->first());
        }

        $user_id = !empty($params['user_id']) ? $params['user_id'] : '';
        $password = !empty($params['password']) ? bcrypt($params['password']) : '';
        $updated_at  = date('Y-m-d H:i:s');

        DB::update("exec api_user_crud 'change_password', $user_id, 'NULL', '" . $password . "', '" . $updated_at . "';");
        $msg = 'Password change successfully!';

        return $this->send_response((object) [], $msg);
    }
}
