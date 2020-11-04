<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications;
use Carbon\Carbon;
use DB;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
    	$notification = Notifications::all();

    	foreach($notification as $list)
    	{
    		$id = $list->id;

    		DB::table('notifications')
                			->where('id',$id)
                			->update(['msg_read' => '1']);
    	}
    	return view('admin.notification.index', compact('notification'));
    }
}