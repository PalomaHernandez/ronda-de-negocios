<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
//use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Model {

	protected $fillable = [
		'email',
		'password',
		'name',
		'activity',
		'location',
		'website',
		'logo_path',
	];

	protected $hidden = [
		'password',
		'remember_token',
	];

}