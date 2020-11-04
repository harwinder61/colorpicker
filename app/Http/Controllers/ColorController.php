<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Color;
use App\ColorPalates;

class ColorController extends Controller
{
    public function index(Request $request)
    {
    	$color_details = Color::get();

    	return view('admin.color.listing',compact('color_details'));
    }

    public function edit_color($id)
    {
        $color = Color::find($id);

        return view('admin.color.edit',compact('color'));
    }

    public function updatecolor(Request $request)
    {
        $color_code = $request->color_code;
        $rgb        = $request->rgb;
        $hsb        = $request->hsb;
        $color_id   = $request->color_id;

        $color = Color::find($color_id);

        $color->color_code = $color_code;
        $color->rgb = $rgb;
        $color->hsb = $hsb;
      

        $color->save();

        return redirect('/admin/colors')->with('success','Color Update Successfully');
    }

    public function delete(Request $request)
    {
        $color_id    = $request->color_id;
        $deletedRows = Color::find($color_id)->delete();
        return redirect('/admin/colors')->with('success','Deleted Successfully');

    }

    public function colorsPalates(Request $request)
    {
        $ColorPalates = ColorPalates::orderBy('id','desc')->get();
        return view('admin.color.color_palates',compact('ColorPalates'));
    }

}
