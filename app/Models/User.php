<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
//use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Silber\Bouncer\Database\Concerns\HasRoles;

class User extends Model {

	use HasRoles, HasApiTokens;

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

	/*
	public function meetingsRequested()
	{
		return $this->hasMany(Meeting::class, 'requester_id');
	}

	public function meetingsReceived()
	{
		return $this->hasMany(Meeting::class, 'receiver_id');
	}*/


}