<?php

namespace Database\Seeders;

use App\Models\Meeting;
use App\Models\User;
use Silber\Bouncer\BouncerFacade;
use App\Models\Event;
use App\Models\Registration;
use App\Models\Image;
use App\Models\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */

    protected static $password;
    public function run(): void{
        foreach(config('roles') as $roleName => $abilities){
			$role = BouncerFacade::role()->create([
				'name' => Str::slug($roleName),
				'title' => $roleName
			]);
			BouncerFacade::allow($role)->to($abilities);
		}
        $users = User::factory(10)->create();
        foreach ($users as $index => $user) {
            if ($index < 2) {
            $user->assign('responsible');
            } else {
            $user->assign('participant');
            }
        }
        $admin = User::factory()->create([
			'email' => 'admin@gmail.com',
			'password'   => static::$password ??= Hash::make('admin'),
		]);
        $admin->assign('administrator');
        
        Event::factory(3)->create();
        File::factory(10)->create();
        Image::factory(10)->create();
        Registration::factory(3)->create();
        \App\Models\Meeting::factory(10)->create();
    }
}
