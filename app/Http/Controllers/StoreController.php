<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Reward;
use App\Tournament;
use App\Store;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $Store_details = Store::all();
    	return view('admin.store.listing', compact('Store_details'));
    }

    public function create(Request $request)
    {
    	return view('admin.store.create');
    }

    public function store(Request $request)
    {
        request()->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($files = $request->file('image')) 
        {
            // Define upload path
               $destinationPath = public_path('/stores/'); // upload path
            // Upload Orginal Image           
               $profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();
               $files->move($destinationPath, $profileImage);

                $store = Store::create([
                    'name'      => $request->get('store_name'),
                    'price'     => $request->get('price'),
                    'number'    => $request->get('store_number'),
                    'store_img' => $profileImage
                ]);

             return redirect('/admin/stores');

        }
    }

    public function edit_store($id)
    {
       $store = Store::find($id);

      return view('admin.store.edit', compact('store'));
    }

    public function updatestore(Request $request)
    {
        $store = Store::find($request->get('user_id'));

        if(($store))
        {
            if(!($request->file('image')))
            {
                $profileImage = $store->store_img;

            }else{

                if ($files = $request->file('image')) 
                {
                    // Define upload path
                   $destinationPath = public_path('/stores/'); // upload path
                    // Upload Orginal Image           
                   $profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();


                   $files->move($destinationPath, $profileImage);
                }
            }
           
            $store->name        = $request->get('store_name');
            $store->price       = $request->get('price');
            $store->number      = $request->get('store_number');
            $store->store_img   = $profileImage;
           
            $store->save();

            return redirect('/admin/stores');
        }
    }

    public function delete(Request $request)
    {
        $user_id    = $request->user_id;
        $deletedRows = Store::find($user_id)->delete();
        return redirect('/admin/stores')->with('success','Deleted Successfully');

    }

    
}
