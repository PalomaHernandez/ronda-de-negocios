<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Silber\Bouncer\Database\Concerns\HasRoles;

class User extends Authenticatable {

	use HasRoles, HasApiTokens, HasFactory;

	protected $fillable = [
		'email',
		'password',
		'name',
		'activity',
		'location',
		'website',
		'logo_public_id',
		'logo_url',
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

	public function images() {
        return $this->hasMany(Image::class);
    }

}