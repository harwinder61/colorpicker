<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Color extends Authenticatable
{
	protected $table = 'color';

	protected $fillable = ['user_id','name','color_code','rgb','hsb']; 

	/**
     * Get the friend_request record associated with the user.
     */
    public function ColorOne()
    {
        return $this->hasOne('App\color_palates','color_id_1');
    }


	/**
     * Get the friend_request record associated with the user.
     */
    public function ColorTwo()
    {
        return $this->hasOne('App\color_palates','color_id_2');
    }
  
}
