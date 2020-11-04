<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Reward;

class RewardController extends Controller
{
    public function index(Request $request)
    {
    	$reward_details = Reward::all();

    	return view('admin.reward.listing',compact('reward_details'));
    }

    public function create(Request $request)
    {
    	return view('admin.reward.create');
    }

    public function store(Request $request)
    {
    	request()->validate([
    		'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    	]);

    	if ($files = $request->file('image')) 
    	{
	       	// Define upload path
	           $destinationPath = public_path('/rewards/'); // upload path
			// Upload Orginal Image           
	           $profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();
	           $files->move($destinationPath, $profileImage);

	           	$rewards = Reward::create([
		            'reward_name' 	=> $request->get('reward_name'),
		            'total_reward'  => $request->get('total_reward'),
		            'reward_image' 	=> $profileImage
		        ]);

		     return redirect('/admin/reward')->with('success','Reward Added Successfully');

        }

    }

    public function edit_reward($id)
    {
        $reward = Reward::find($id);

      return view('admin.reward.edit', compact('reward'));
    }

    public function updatereward(Request $request)
    {
        $reward = Reward::find($request->get('user_id'));

        if(($reward))
        {
            if(!($request->file('image')))
            {
                $profileImage = $reward->reward_image;

            }else{

                if ($files = $request->file('image')) 
                {
                    // Define upload path
                   $destinationPath = public_path('/rewards/'); // upload path
                    // Upload Orginal Image           
                   $profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();


                   $files->move($destinationPath, $profileImage);
                }
            }
           
            $reward->reward_name        = $request->get('reward_name');
            $reward->total_reward       = $request->get('total_reward');
            $reward->reward_image   = $profileImage;
           
            $reward->save();

            return redirect('/admin/reward')->with('success','Reward Updated Successfully');
        }
    }

    public function delete(Request $request)
    {
        $user_id    = $request->user_id;
        $deletedRows = Reward::find($user_id)->delete();
        return redirect('/admin/reward')->with('success','Deleted Successfully');

    }
}
