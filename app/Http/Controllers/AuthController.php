<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'signup','OAuth']]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ($token = $this->guard()->attempt($credentials)) {
            return $this->respondWithToken($token);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function OAuth(Request $request, $provider)
    {
        if ($provider == 'google')
            $user = User::where('google_id', $request->id)->first();
        else if ($provider == 'facebook')
            $user = User::where('facebook_id', $request->id)->first();

        if (!$user) {
            $validator = Validator::make($request->all(), [
                'fullname' => 'required',
                'email'      => 'string|email|unique:users|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => 0,
                    'message' => $validator->errors()
                ], 400);
            }
            $newUser = new User();
            $newUser->firstname = $request->fullname;
            $newUser->email     = $request->email;
            $newUser->role_id     = '3';
            $newUser->mobile_no = isset($request->mobile_no)?$request->mobile_no:null;
            if ($provider == 'google')
                $newUser->google_id = $request->id;
            if ($provider == 'facebook')
                $newUser->facebook_id = $request->id;
            $newUser->save();

            $token = Auth::login($newUser);
            return response()->json([
                'status' => 'success',
                'message' => 'User logined with ' . $provider . ' successfully',
                'user' => $newUser,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
        } else {
            $token = Auth::login($user);
            return response()->json([
                'status' => 'success',
                'message' => 'User logined with ' . $provider . ' successfully',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
        }
    }


    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname'  => 'required',
            'email'      => 'string|email|unique:users|max:100',
            'mobile_no' => [
                'required'
            ],
            'password'   => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()
            ], 400);
        }
        $user = new User();
        $user->firstname = $request->firstname;
        $user->lastname  = $request->lastname;
        $user->email     = $request->email;
        $user->mobile_no = $request->mobile_no;
        $user->password = bcrypt($request->password);
        $user->save();

        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }
    public function userProfile()
    {
        return response()->json(auth()->user());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 36000,
            'user' => auth()->user()

        ]);
    }
    public function guard()
    {
        return Auth::guard();
    }
}
