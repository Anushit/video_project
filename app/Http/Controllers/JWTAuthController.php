<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Http\Request;
use App\User;
use App\UserDetails;
use App\ForgotPasswordOtpCode;
use JWTAuth;
use Hash;
use App\Rules\MatchOldPassword;

class JWTAuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    protected $authToken = '';
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register','forgotPassword','resetPassword']]);
        $this->authToken = auth()->guard('api')->check();
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name'         => 'required',
            'username'         => 'required|unique:users,username,Y,removed',
            'email'         => 'required|unique:users,email,Y,removed',
            'phone'         => 'required|unique:users,phone,Y,removed|max:12',
            'profile_image'       => 'mimes:jpeg,jpg,png|max:10000',
            'dob'         => 'required|date',
            'doj'         => 'required|date',
        ]);

        if ($validator->fails()) {
            $arr = [];
            $valid = json_decode($validator->errors());
            foreach($valid as $key => $value) {
                $arr[$key] = $value[0];
            }

            return response()->json([
                "success" => false,
                "message" => "Bad Request",
                "data" =>  $arr
            ], 400);
        }

        $slug = md5($request->username.date('ymdhisa'));
        $data = new User();
        $data->name = $request->name;
        $data->slug = $slug;
        $data->type = 2;
        $data->username = $request->username;
        if ($files = $request->file('profile_image')) {
            $files = $request->file('profile_image');
            $image = ImageResize('public/upload/images/profile_image/',array("height"=>50,"width"=>100),$files);
            $data->image = $image;
        }
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->password = Hash::make($request->phone);
        $data->address = $request->address;
        $data->save();

        $userDetailsData = new UserDetails();
        $userDetailsData->user_id = $data->id;
        $userDetailsData->dob = $request->dob;
        $userDetailsData->doj = $request->doj;
        $userDetailsData->save();

        return response()->json([
            "success" => true,
            "message" => "Api run Successfully.",
            "data" => []
        ], 200);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'phone' => 'required|max:12',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        $user = User::where([['phone', '=', $request->phone],['type',2],['removed', '=', 'N']])->get();
        
        if($user->count() <= 0) {
            return response()->json([
                "success" => false,
                "message" => "This Mobile No. is not exist",
            ], 404);    
        } elseif(!\Hash::check($request->password, $user->first()->password)) {
            return response()->json([
                'success' => false,
                'message' => 'This password is not correct'
            ], 404);

        } elseif($user->first()->status == 1) {
            return response()->json([
                "success" => false,
                "message" => "Your account is blocked",
            ], 403);
        }

        $token = JWTAuth::fromUser($user->first());
        if (!$token) {
            return response()->json([
                "success" => false,
                "message" => "Unauthorized",
            ], 401);
        }

        return $this->createNewToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        return response()->json(["success" => true,
                    "message" => "Api Run Successfully",
                    "data"=>[auth('api')->user()]]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            "success" => true,
            "message" => "Login Successfully",
            "data"=>[
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => auth('api')->factory()->getTTL() * 600
                ]
        ]);
    }


    public function forgotPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            $arr = [];
            $valid = json_decode($validator->errors());
            foreach($valid as $key => $value) {
                $arr[$key] = $value[0];
            }

            return response()->json([
                "success" => false,
                "message" => "Bad Request",
                "data" =>  $arr
            ], 400);
        }

        $user = User::where([['email', '=', $request->email],['removed', '=', 'N']]);

        if($user->count() <= 0) {
            
            return response()->json([
                "success" => false,
                "message" => "This email is not exist",
            ], 404);    

        }  elseif($user->first()->status == 1) {
            
            return response()->json([
                "success" => false,
                "message" => "Your account is blocked",
            ], 403);

        } else {
            $user_id = $user->first()->id;
            $data_code = ForgotPasswordOtpCode::where('user_id', $user_id);
            
            if($data_code->count() > 0 && strtotime($data_code->first()->expire_on) >= strtotime(date('YmdHis'))) {
                return response()->json([
                    "success" => true,
                    "message" => "OTP sent Successfully",
                    "data" => ['otp' => $data_code->first()->otp],
                ], 200);

            } else {

                ForgotPasswordOtpCode::where('user_id', $user_id)->delete();

                $rand = rand(1000, 9999);
                $otp = new ForgotPasswordOtpCode;
                $otp->user_id = $user_id;
                $otp->otp = $rand;
                $otp->expire_on = date('Y-m-d H:i:s', strtotime('+15 minutes'));
                $otp->save();

                return response()->json([
                    "success" => true,
                    "message" => "OTP sent Successfully",
                    "data" => ['otp' => $rand],
                ], 200);
            }
        }
    }

    public function resetPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'otp' => 'required|exists:forgot_password_otp_codes',
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        if ($validator->fails()) {
            $arr = [];
            $valid = json_decode($validator->errors());
            foreach($valid as $key => $value) {
                $arr[$key] = $value[0];
            }

            return response()->json([
                "success" => false,
                "message" => "Bad Request",
                "data" =>  $arr
            ], 400);
        }

        $user = User::where([['email', '=', $request->email],['removed', '=', 'N']]);
        if($user->count() <= 0) {
            
            return response()->json([
                "success" => false,
                "message" => "This email is not exist",
            ], 404);    

        }else{
            $user_id = $user->first()->id;
            $data_code = ForgotPasswordOtpCode::where('user_id', $user_id);
            
            if($data_code->count() > 0 && strtotime($data_code->first()->expire_on) <= strtotime(date('YmdHis'))) {
                return response()->json([
                    "success" => false,
                    "message" => "OTP is expired",
                    "data" => [],
                ], 401);
            }else{
                $user = User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);
                    ForgotPasswordOtpCode::where('user_id', $user_id)->delete();
                return response()->json([
                    "success" => true,
                    "message" => "Password Changed Successfully",
                    "data" => [],
                ], 200);
            }
        }
    }

    public function ChangePassword(Request $request){
        $data = auth('api')->user()->toArray();
        $id = $data['id'];
        $validator = Validator::make($request->all(), [
            'old_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'confirm_password' => 'required|same:new_password',
        ]);
        if ($validator->fails()) {
            $arr = [];
            $valid = json_decode($validator->errors());
            foreach($valid as $key => $value) {
                $arr[$key] = $value[0];
            }

            return response()->json([
                "success" => false,
                "message" => "Bad Request",
                "data" =>  $arr
            ], 400);
        }

        User::find($id)->update(['password'=> \Hash::make($request->new_password)]);
        toastr()->success('Password Changed Successfully');
        return response()->json([
                    "success" => true,
                    "message" => "Password Changed Successfully",
                    "data" => [],
                ], 200);
    }
}
