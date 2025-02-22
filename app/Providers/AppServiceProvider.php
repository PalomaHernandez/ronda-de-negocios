<?php

namespace App\Providers;


use App\Http\Controllers\EventController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\UserController;
use App\Repositories\Interfaces\EventRepository;
use App\Repositories\EventRepositoryImpl;
use App\Repositories\Interfaces\MeetingRepository;
use App\Repositories\MeetingRepositoryImpl;
use App\Repositories\Interfaces\RegistrationRepository;
use App\Repositories\RegistrationRepositoryImpl;
use App\Repositories\Interfaces\UserRepository;
use App\Repositories\UserRepositoryImpl;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->when(EventController::class)
			->needs(EventRepository::class)
			->give(EventRepositoryImpl::class);

        $this->app->when(EventController::class)
            ->needs(UserRepository::class)
            ->give(UserRepositoryImpl::class);
            
		$this->app->when(MeetingController::class)
			->needs(MeetingRepository::class)
			->give(MeetingRepositoryImpl::class);

		$this->app->when(RegistrationController::class)
			->needs(RegistrationRepository::class)
			->give(RegistrationRepositoryImpl::class);

        $this->app->when(RegistrationController::class)
			->needs(UserRepository::class)
			->give(UserRepositoryImpl::class);

		$this->app->when(UserController::class)
			->needs(UserRepository::class)
			->give(UserRepositoryImpl::class);

        $this->app->when(UserController::class)
			->needs(RegistrationRepository::class)
			->give(RegistrationRepositoryImpl::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
