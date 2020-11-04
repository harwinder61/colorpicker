<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ColorPalates extends Authenticatable
{
	protected $table = 'color_palates';

	protected $fillable = ['user_id','name','color_id_1','colorcode1','rgb1','hsb1','color_id_2','colorcode2','rgb2','hsb2']; 

	/**
     * Get the user that owns the phone.
     */
    public function color()
    {
        return $this->belongsTo('App\Color');
    }
  
}
