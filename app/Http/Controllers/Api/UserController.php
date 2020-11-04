<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Reward;
use App\Tournament;
use App\Store;
use App\User_token;
use App\Friend_request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use DB;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public $successStatus       = true;
    public $failStatus          = false;
    public $unauthorisedStatus  = 401;

/******************************************
           LOGIN API
*******************************************/
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $destinationPath = url()->previous();

        try {
            if (! $token = JWTAuth::attempt($credentials)) {

                $data = [];

                return response()->json([
                    'status' => $this->failStatus,
                    'message'=> "Invalid Credentials",
                    'data'   => $data
                ], 400);
            }
        } catch (JWTException $e) {

            $data = [];

            return response()->json([
                'status' => $this->failStatus,
                'message'=> "Could not create token",
                'data'   => $data
            ], 400);

           
        }
        $user_detail = User::where('email',$request->get('email'))->first();

        if(!($user_detail->user_img))
        {
            $img = $destinationPath.'/public/users/default-profile.png';
        }else{
            $img = $destinationPath.'/public/users/'.$user_detail->user_img;
        }

        $data = array(
                'user_id' => (isset($user_detail->id)) ? $user_detail->id : '' ,
                'name'    => (isset($user_detail->name)) ? $user_detail->name : '' ,
                'email'   => (isset($user_detail->email)) ? $user_detail->email : '' ,
                'dob'   => (isset($user_detail->dob)) ? $user_detail->dob : '' ,
                'gender'   => (isset($user_detail->gender)) ? $user_detail->gender : '' ,
                'user_image' => $img,
                'token'   => $token
        );

        return response()->json([
            'status' => $this->successStatus,
            'message'=> "Login Success",
            'data'   => [$data]
        ], 200);
    }

/******************************************
           REGISTER API
*******************************************/

    public function register(Request $request)
    {

        $UsersDetail = User::where('email',$request->get('email'))->first();

        if($UsersDetail)
        {
            $data = [];
            return response()->json([
                'status' => $this->failStatus,
                'message' => "Please enter unique email address",
                'data'   => $data
            ], 400);
        }
       

        $user = User::create([
            'name'          => $request->get('name'),
            'email'         => $request->get('email'),
            'password'      => Hash::make($request->get('password')),
            'type'          => $request->get('type'),
            'device_type'   => $request->get('device_type'),
            'device_token'  => $request->get('device_token'),
            
        ]);

        $userID = $user->id;
        $token = JWTAuth::fromUser($user);
        $data = array(
                'id'            => $userID,
                'name'          => $request->get('name'),
                'email'         => $request->get('email'),
                'type'          => $request->get('type'),
                'device_type'   => $request->get('device_type'),
                'device_token'   => $request->get('device_token'),
                
                            
        );

        return response()->json([
            'status' => $this->successStatus,
            'message'=> "Sign up successfully",
            'data'   => [$data]
        ], 200);

        
    }

/******************************************
           FORGOT PASSWORD  API
*******************************************/

    public function forgot_pswd(Request $request)
    {
        $input = $request->all();
        
        if(empty($request->email))
        {
            $data = [];

            return response()->json([
                'status' => $this->failStatus,
                'message'=> "Email field is required",
                'data'   => $data
            ], 400);
 
        }
        
        $user = User::where('email', '=', $request->email)->first();

        if ($user === null) 
        {
            $data = [];

            return response()->json([
                'status' => $this->failStatus,
                'message'=> "Please enter a valid email address.",
                'data'   => $data
            ], 400);

        }

        $check_username = User::where('email', $request->email)->first();

        if(!empty($check_username))
        {
            $password = uniqid();
            $msg = "Your Password:".$password;
            $msg = wordwrap($msg,70);  

            mail($request->email,"Forget Password",$msg);       
            
            $check_username->password = bcrypt($password);
            $check_username->save();

            $data = [];

            return response()->json([
                'status' => $this->successStatus,
                'message'=> "please your check email.",
                'data'   => $data
            ], 200);

        }else{

            $data = [];

            return response()->json([
                'status' => $this->failStatus,
                'message'=> "Failure.",
                'data'   => $data
            ], 400);
        }
    }

/***************************************************
    LOGIN WITH GOOGLE
*************************************************/
    public function social_login(Request $request)
    {
        $google_details = $request->all();

        $user_name  = $google_details['name'];
        $email      = $google_details['email'];
        // $dob      = $google_details['dob'];
        // $gender      = $google_details['gender'];
        $login_with       = $google_details['login_with'];
        $device_token = $google_details['device_token'];
        $device_type  = $google_details['device_type'];

        $user_detail = User::where('email',$email)->first();

        if(!($user_detail))
        {
            $user = User::create($google_details);
             $token = JWTAuth::fromUser($user); 
            
            $data = array(
                    'user_id' => $user->id,
                    'name'  => $user_name,
                    'email' => $email,
                    // 'dob' => $dob,
                    // 'gender' => $gender,
                    'token'  => $token,
                    'login_with' => $login_with

            );

            return response()->json([
                'status' => $this->successStatus,
                'message' => "Login Success",
                'data'  => [$data]
            ], 200);

        }else{

            $token = JWTAuth::fromUser($user_detail); 
            
            DB::table('users')
                ->where('email',$email)
                ->update(['name' => $user_name, 'email' => $email,'login_with'=>$login_with]);

            $destinationPath = url()->previous();

            if(!($user_detail->user_img))
            {
                $UserImg = $destinationPath.'/public/users/default-profile.png';

            }else{

                $UserImg = $destinationPath.'/public/users/'.$user_detail->user_img;
            }

            $data = array(
                    'user_id'       => (isset($user_detail->id)) ? $user_detail->id : '', 
                    'name'          => (isset($user_detail->name)) ? $user_detail->name : '', 
                    'email'         => (isset($user_detail->email)) ? $user_detail->email : '', 
                    'dob'           => (isset($user_detail->dob)) ? $user_detail->dob : '', 
                    'gender'        => (isset($user_detail->gender)) ? $user_detail->gender : '', 
                    'image'         => $UserImg,
                    'token'         => $token,
                    

            );
            return response()->json([
                'status' => $this->successStatus,
                'message' => "Login Success",
                'data'  => [$data]
            ], 200);
        }
    }

/******************************************
           USER DETAILS API
*******************************************/

    public function getAuthenticatedUser()
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                $data = [];

                return response()->json([
                    'status' => $this->failStatus,
                    'message'=> "User not found",
                    'data'   => $data
                ], 400);

            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            $data = [];

            return response()->json([
                'status' => $this->failStatus,
                'message'=> "Token expired",
                'data'   => $data
            ], 400);

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            $data = [];

            return response()->json([
                'status' => $this->failStatus,
                'message'=> "Token Invalid",
                'data'   => $data
            ], 400);

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            $data = [];

            return response()->json([
                'status' => $this->failStatus,
                'message'=> "Token Absent",
                'data'   => $data
            ], 400);

        }
        $destinationPath = url()->previous();

        if(!($user->user_img))
        {
            $UserImg = $destinationPath.'/public/users/default-profile.png';

        }else{

            $UserImg = $destinationPath.'/public/users/'.$user->user_img;
        }

        $data = array(
                    'user_id'       => (isset($user->id)) ? $user->id : '' ,
                    'name'          => (isset($user->name)) ? $user->name : '' ,
                    'email'         => (isset($user->email)) ? $user->email : '' ,
                    'gender'         => (isset($user->gender)) ? $user->gender : '' ,
                    'dob'               => (isset($user->dob)) ? $user->dob : '' ,
                    'device_type'         => (isset($user->device_type)) ? $user->device_type : '' ,
                    'device_token'         => (isset($user->device_token)) ? $user->device_token : '' ,
                    'image'         => $UserImg
                    
        );
        return response()->json([
                'status' => $this->successStatus,
                'message'=> "User found",
                'data'   => [$data]
        ], 200);
       
    }



/****************************************
    EDIT USER PROFILE
*******************************************/
    public function update_user_profile(Request $request)
    {
        $user_id = $request->user_id;

        if(!($user_id))
        {
            $data = [];
            return response()->json([
                'status' => $this->failStatus,
                'message' => "User not found",
                'data'  => $data
            ], 400);

        }else{

            $user_detail = User::find($user_id);

            if(!($user_detail))
            {
                $data = [];
                return response()->json([
                    'status' => $this->failStatus,
                    'message' => "User not found",
                    'data'  => $data
                ], 400);

            }else{

                $destinationPath = url()->previous();

                if(!($user_detail->user_img))
                {
                    $UserImg = $destinationPath.'/public/users/default-profile.png';

                }else{

                    $UserImg = $destinationPath.'/public/users/'.$user_detail->user_img;
                }


                $user_detail->name      = $request->name;
                $user_detail->gender    = $request->gender;
                $user_detail->dob       = $request->dob;
               

                $user_detail->save();

                $data = array(
                            'user_id'   => (isset($user_detail->id)) ? $user_detail->id : '',  
                            'name'      => (isset($user_detail->name)) ? $user_detail->name : '',  
                            'email'     => (isset($user_detail->email)) ? $user_detail->email : '',  
                            'gender'    => (isset($user_detail->gender)) ? $user_detail->gender : '', 
                            'dob'       => (isset($user_detail->dob)) ? $user_detail->dob : '', 
                            'image'     => $UserImg
                );
                return response()->json([
                    'status' => $this->successStatus,
                    'message' => "User profile update successfully",
                    'data'  => [$data]
                ], 200);
            }
        }
    }

/*********************************************
    UPLOAD IMAGE API
**********************************************/
    public function upload_image(Request $request)
    {
        $user_id        = $request->get('user_id');
        $user_details   = User::find($user_id); 



        if(!($user_details))
        {
            $data = [];

            return response()->json([
                'status' => $this->failStatus,
                'message'=> "User not found",
                'data'   =>$data,

            ], 400);

        }else{


            request()->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            if ($files = $request->file('image')) 
            {
               // die('data');
                // Define upload path
                $destinationPathFolder = url()->previous();
               $destinationPath = public_path('/users/'); // upload path
                // Upload Orginal Image           
               $profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();
               $files->move($destinationPath, $profileImage);

                DB::table('users')
                ->where('id',$user_details->id)
                ->update(['user_img' => $profileImage]);

                $data = array(
                    'user_id'   => $user_details->id,
                    'name'      => (isset($user_details->name)) ? $user_details->name : '', 
                    'email'     => (isset($user_details->email)) ? $user_details->email : '',
                    'dob'       => (isset($user_details->dob)) ? $user_details->dob : '', 
                    'gender'    => (isset($user_details->gender)) ? $user_details->gender : '', 
                    'type'      => (isset($user_details->type)) ? $user_details->type : '',
                    'user_img'  => $destinationPathFolder.'/public/users/'.$profileImage,
                    'device_type'      => (isset($user_details->device_type)) ? $user_details->device_type : '',
                    'device_token'      => (isset($user_details->device_token)) ? $user_details->device_token : ''
                );

                return response()->json([
                    'status' => $this->successStatus,
                    'message'=> "Image upload successfully",
                    'data'   => [$data],

                ], 200);
                
            }

        }
            
    }

}
