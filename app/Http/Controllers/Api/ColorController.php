<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Color;
use App\ColorPalates;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use DB;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;

class ColorController extends Controller
{
    public $successStatus       = true;
    public $failStatus          = false;
    public $unauthorisedStatus  = 401;



/******************************************
           COLOR ADD  API
*******************************************/    
    public function add_color(Request $request)
    {
        $user_id    = $request->user_id;
        $color_code = $request->color_code;
        $rgb        = $request->rgb;
        $hsb        = $request->hsb;
        $name       = $request->name;

        if(empty($user_id))
        {
            $data = [];
            return response()->json([
                'status' => $this->failStatus,
                'message' => "User not found",
                'data'  => $data
            ], 400);

        }else{
            $color = Color::create([
                        'user_id'       => $user_id,
                        'color_code'    => $color_code,
                        'rgb'           => $rgb,
                        'hsb'           => $hsb,
                        'name'          => $name
            ]);

            $data = array(
                            'id'            => $color->id,
                            'user_id'       => $user_id,
                            'name'          => $name,
                            'color_code'    => $color_code,  
                            'rgb'           => $rgb,  
                            'hsb'           => $hsb, 
                            
            );

            return response()->json([
                'status' => $this->successStatus,
                'message' => "color add successfully",
                'data'  => [$data]
            ], 200);
        }
    }

/*************************************************
    GET ALL COLOR API
*****************************************************/
    public function getallcolor(Request $request)
    {
        $user_id = $request->user_id;

        if(empty($user_id))
        {
            $data = [];
            return response()->json([
                'status' => $this->failStatus,
                'message' => "User not found",
                'data'  => $data
            ], 400);

        }else{
            $get_color = Color::where('user_id',$user_id)->orderBy('id','desc')->get();

            if(count($get_color) == 0)
            {
                $data = [];
                return response()->json([
                    'status' => $this->failStatus,
                    'message' => "color not found",
                    'data'  => $data
                ], 400);

            }else{
               foreach($get_color as $value)
                {
                    $data[] = array(
                                'id'            => $value->id,
                                'user_id'       => $value->user_id,
                                'color_code'    => $value->color_code,
                                'rgb'           => $value->rgb,
                                'hsb'           => $value->hsb
                    );
                }

                 return response()->json([
                'status' => $this->successStatus,
                'message' => "color found successfully",
                'data'  => $data
                ], 200);
            }
        }
    }

/****************************************
    UPDATE COLOR API
*********************************************/

    public function updateColor(Request $request)
    {
        $id         = $request->id;
        $user_id    = $request->user_id;
        $color_code = $request->color_code;
        $rgb        = $request->rgb;
        $hsb        = $request->hsb;
        $name       = $request->name;

        if(empty($id))
        {
            $data = [];
            return response()->json([
                'status' => $this->failStatus,
                'message' => "color not found",
                'data'  => $data
            ], 400);

        }elseif(empty($user_id))
        {
            $data = [];
            return response()->json([
                'status' => $this->failStatus,
                'message' => "User not found",
                'data'  => $data
            ], 400);

        }else{
            $get_color = Color::find($id);

            if(empty($get_color))
            {   
                $data = [];
                return response()->json([
                    'status' => $this->failStatus,
                    'message' => "color not found",
                    'data'  => $data
                ], 400);

            }else{

                $get_color->user_id     = $user_id;
                // $get_color->color_code  = $color_code;
                $get_color->name        = $name;
                // $get_color->rgb         = $rgb;
                // $get_color->hsb         = $hsb;
                $get_color->save();

                $data = array(
                                'id'            => $id,
                                'user_id'       => $user_id,
                                'color_code'    => $color_code,
                                'name'          => $name,
                                'rgb'           => $rgb,
                                'hsb'           => $hsb
                );

                return response()->json([
                    'status' => $this->successStatus,
                    'message' => "color Update successfully",
                    'data'  => [$data]
                ], 200);
            }
        }

    }

/***************************************
    DELETE COLOR API
**************************************/
    public function deleteColor(Request $request)
    {
        $id = $request->id;

        if(empty($id))
        {
            $data = [];
            return response()->json([
                'status' => $this->failStatus,
                'message' => "color not found",
                'data'  => $data
            ], 400);
        }else{
            $get_color = Color::find($id);

            if(empty($get_color))
            {
                $data = [];
                return response()->json([
                    'status' => $this->failStatus,
                    'message' => "color not found",
                    'data'  => $data
                ], 400);

            }else{

                $get_color->delete();

                return response()->json([
                    'status' => $this->successStatus,
                    'message' => "color delete successfully",
                    'data'  => []
                ], 200);
            }
        }
    }

/********************************************
        ADD COLOR PALATES
**********************************************/
    public function ColorPalates(Request $request)
    {
        $user_id = $request->user_id;
        $color_id_1 = $request->color_id_1;
        $color_id_2 = $request->color_id_2;
        $name       = $request->name;

        if(empty($user_id))
        {
            $data = [];
            return response()->json([
                'status' => $this->failStatus,
                'message' => "User not found",
                'data'  => $data
            ], 400);

        }elseif(empty($color_id_1))
        {
            $data = [];
            return response()->json([
                'status' => $this->failStatus,
                'message' => "color not found",
                'data'  => $data
            ], 400);

        }elseif(empty($color_id_2))
        {
            $data = [];
            return response()->json([
                'status' => $this->failStatus,
                'message' => "color not found",
                'data'  => $data
            ], 400);
        }else{
            $color_palates = ColorPalates::where('user_id',$user_id)->where('color_id_1',$color_id_1)->where('color_id_2')->first();

            if(empty($color_palates))
            {
                $color1 = Color::find($color_id_1);

                $color2 = Color::find($color_id_2);

                $color = ColorPalates::create([
                                    'user_id'       => $user_id,
                                    'name'          => $name,
                                    'color_id_1'    => $color_id_1,
                                    'colorcode1'    => $color1->color_code,
                                    'rgb1'          => $color1->rgb,
                                    'hsb1'          => $color1->hsb,
                                    'color_id_2'    => $color_id_2,
                                    'colorcode2'    => $color2->color_code,
                                    'rgb2'          => $color2->rgb,
                                    'hsb2'          => $color2->hsb
                ]);

                $data = array(
                            'id'            => $color->id,
                            'user_id'       => $user_id,
                            'name'          => $name,
                            'color_id_1'    => $color_id_1,
                            'color_id_2'    => $color_id_2
                );

                return response()->json([
                    'status' => $this->successStatus,
                    'message' => "color palates add successfully",
                    'data'  => [$data]
                ], 200);

            }else{

                $data = [];
                return response()->json([
                    'status' => $this->failStatus,
                    'message' => "Color Palates already exist",
                    'data'  => $data
                ], 400);
            }
        }
    }

/*************************************
    DELETE COLOR PALATES
***************************************/    
    public function DeleteColorPalates(Request $request)
    {
        $id = $request->id;

        if(empty($id))
        {
            $data = [];
            return response()->json([
                'status' => $this->failStatus,
                'message' => "color not found",
                'data'  => $data
            ], 400);
        }else{
            $getcolor = ColorPalates::find($id);

            if(empty($getcolor))
            {
                $data = [];
                return response()->json([
                    'status' => $this->failStatus,
                    'message' => "color not found",
                    'data'  => $data
                ], 400);
            }else{
                $getcolor->delete();

                return response()->json([
                    'status' => $this->successStatus,
                    'message' => "color palates delete successfully",
                    'data'  => []
                ], 200);
            }
        }

    }

/****************************************
    GET COLOR PALATES
*****************************************/
    public function GetColorPalates(Request $request)
    {
        $user_id = $request->user_id;

        if(empty($user_id))
        {
            $data = [];
            return response()->json([
                'status' => $this->failStatus,
                'message' => "User not found",
                'data'  => $data
            ], 400);

        }else{

            $color = ColorPalates::where('user_id',$user_id)->get();



            if(count($color) == 0)
            {
                $data = [];
                return response()->json([
                    'status' => $this->failStatus,
                    'message' => "Color Palates not found",
                    'data'  => $data
                ], 400);
            }else{

                foreach($color as $value)
                {
                    $data[] = array(
                            'id'             => $value->id,
                            'user_id'       => $value->user_id,
                            'name'          => $value->name,
                            'color_id_1'    => $value->color_id_1,
                            'colorcode1'    => $value->colorcode1,
                            'rgb1'          => $value->rgb1,
                            'hsb1'          => $value->hsb1,
                            'color_id_2'    => $value->color_id_2,
                            'colorcode2'    => $value->colorcode2,
                            'rgb2'          => $value->rgb2,
                            'hsb2'          => $value->hsb2
                    ); 
                }
            }

            return response()->json([
                    'status' => $this->successStatus,
                    'message' => "color palates found successfully",
                    'data'  => $data
            ], 200);
        }
    }

/*************************************************
    UPDATE COLOR PALATES
*******************************************************/
    public function UpdateColorPalates(Request $request)
    {
        $id             = $request->id;
        $user_id        = $request->user_id;
        $name           = $request->name;
        $color_id_1     = $request->color_id_1;
        $colorcode1     = $request->colorcode1;
        $rgb1           = $request->rgb1;
        $hsb1           = $request->hsb1;
         $color_id_2    = $request->color_id_2;
        $colorcode2     = $request->colorcode2;
        $rgb2           = $request->rgb2;
        $hsb2           = $request->hsb2;
       

        if(empty($id))
        {
            $data = [];
            return response()->json([
                'status' => $this->failStatus,
                'message' => "Color Palates not found",
                'data'  => $data
            ], 400);

        }else{
            $color = ColorPalates::find($id);

            if(empty($color))
            {
                 $data = [];
                return response()->json([
                    'status' => $this->failStatus,
                    'message' => "Color Palates not found",
                    'data'  => $data
                ], 400);

            }else{
                $color->user_id = $user_id;
                $color->name    = $name;
                // $color->color_id_1 = $color_id_1;
                // $color->colorcode1 = $colorcode1;
                // $color->rgb1 = $rgb1;
                // $color->hsb1 = $hsb1;
                // $color->color_id_2 = $color_id_2;
                //  $color->colorcode2 = $colorcode2;
                // $color->rgb2 = $rgb2;
                // $color->hsb2 = $hsb2;

                $color->save();

                $data = array(
                            'id'            => $id,
                            'user_id'       => $user_id,
                            'name'          => $name,
                            'color_id_1'    => $color_id_1,
                            'color_id_2'    => $color_id_2
                );

                return response()->json([
                    'status' => $this->successStatus,
                    'message' => "color palates update successfully",
                    'data'  => [$data]
                ], 200);
            }
        }

    }


}
