<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications;
use App\Friend_request;
use App\User;
use Carbon\Carbon;
use DB;

class FriendController extends Controller
{

	public function get_fav_unfav(Request $request)
	{
		$fav_details = DB::table('users')->join('friend_requests', 'users.id', '=', 'friend_requests.friend_id')->get();

		return view('admin.friend.fav_unfav', compact('fav_details'));
	}

	public function get_frnd_request(Request $request)
	{
		$data = DB::table('users')
		       ->join('friend_requests', 'users.id', '=', 'friend_requests.user_id')
		       ->get();

		foreach($data as $detail)
		{
			$frnd_id = $detail->friend_id;

			$friend_detail = DB::table('users')
								->join('friend_requests', 'users.id', '=', 'friend_requests.friend_id')
								->select('users.name as frnd_name', 'users.user_img as frnd_img','friend_requests.created_at as created_at')
								->get();
		}

		foreach($friend_detail as $record)
		{
			$final_data[] = array(
							'user_id'  => $detail->user_id,
							'username' => $detail->name,
							'email'    => $detail->email,
							'user_img' => $detail->user_img,
							'friend_id' => $detail->friend_id,
							'friend_name' => $record->frnd_name,
							'friend_image' => $record->frnd_img,
							'date'         => $record->created_at
			);
		}
		return view('admin.friend.request', compact('final_data'));
	}
}