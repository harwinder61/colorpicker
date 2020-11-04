<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Reward;
use App\Tournament;

class TournamentController extends Controller
{
    public function index(Request $request)
    {
        $tournamet_details = Tournament::all();
    	return view('admin.tournament.listing',compact('tournamet_details'));
    }

    public function create(Request $request)
    {
    	return view('admin.tournament.create');
    }

    public function store(Request $request)
    {

    	request()->validate([
    		'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    	]);

    	if ($files = $request->file('image')) 
    	{
	       	// Define upload path
	           $destinationPath = public_path('/tournaments/'); // upload path
			// Upload Orginal Image           
	           $profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();
	           $files->move($destinationPath, $profileImage);

	           	$tournament = Tournament::create([
		            'tournament_name' 	=> $request->get('tournament_name'),
		            'days'              => $request->get('days'),
		            'tournamant_image' 	=> $profileImage,
                    'total_player'      => $request->get('total_player'),
                    'price'             => $request->get('price')
		        ]);

		     return redirect('/admin/tournament');

        }
    }

    public function edit_tournament($id)
    {
       $tournament = Tournament::find($id);

      return view('admin.tournament.edit', compact('tournament'));
    }

    public function updatetournament(Request $request)
    {
        $tournament = Tournament::find($request->get('user_id'));

        if(($tournament))
        {
            if(!($request->file('image')))
            {
                $profileImage = $tournament->tournamant_image;

            }else{

                if ($files = $request->file('image')) 
                {
                    // Define upload path
                   $destinationPath = public_path('/tournaments/'); // upload path
                    // Upload Orginal Image           
                   $profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();


                   $files->move($destinationPath, $profileImage);
                }
            }
           
            $tournament->tournament_name        = $request->get('tournament_name');
            $tournament->days                   = $request->get('days');
            $tournament->tournamant_image       = $profileImage;
            $tournament->total_player           = $request->get('total_player');
            $tournament->price                  = $request->get('price');
           
            $tournament->save();

            return redirect('/admin/tournament')->with('success','Updated Successfully');
        }
    }

    public function delete(Request $request)
    {
        $user_id    = $request->user_id;
        $deletedRows = Tournament::find($user_id)->delete();
        return redirect('/admin/tournament')->with('success','Deleted Successfully');

    }

    
}
