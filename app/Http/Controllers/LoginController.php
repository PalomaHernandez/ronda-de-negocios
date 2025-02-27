<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\EventRepository;
use App\Repositories\Interfaces\RegistrationRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class LoginController extends Controller
{
	public function __construct(private readonly EventRepository $eventRepository, private readonly RegistrationRepository $registrationRepository){}
	public function index()
	{
		return view('auth.login');
	}

	public function login(Request $request)
	{
		$this->validateLogin();

		if (!Auth::attempt($this->credentials())) {
			if(request()->expectsJson()){
				return response()->json(['message' => 'Credenciales inválidas.'], 401);
			}
			return redirect()->back()->with('error', 'Credenciales inválidas.');
		}

		$user = Auth::user()->load('images');
		
		if(request()->expectsJson()){
			$event_slug = $request->input('eventSlug');
			$role = $user->roles->pluck('name');

			$token = $user->createToken('auth_token')->plainTextToken;
			$isResponsible = false;
			$isRegistered = false;
			$registration = null;

			if($role->contains('responsible')){
				$isResponsible = $this->eventRepository->isResponsible($user->id,$event_slug);
			} 
			
			if(!$isResponsible){
				$registration = $this->registrationRepository->userRegistration($event_slug);
				if ($registration) {
					$isRegistered = true;
				}
			}
		
			return response()->json([
				'user' => $user,
				'token' => $token,
				'isResponsible' => $isResponsible,
       			'isRegistered' => $isRegistered,
				'registration' => $registration,
			]);
		}

		return redirect()->route('home');
	}

	protected function validateLogin(): void
	{
		request()->validate([
			'email' => 'required|email',
			'password' => 'required',
		]);
	}

	protected function attemptLogin(): bool
	{
		return auth()->attempt($this->credentials());
	}

	protected function credentials(): array
	{
		return request()->only('email', 'password');
	}

	public function logout(Request $request)
	{
		$user = Auth::user();
		Log::info('User logged out', ['user' => $user]);

		if (!$user) {
			return response()->json(['message' => 'Usuario no autenticado.'], 401);
		}

		if (request()->expectsJson()) {
			$user->tokens()->delete();
			return response()->json(['message' => 'Cierre de sesión exitoso']);
		}

		Auth::logout();
		return redirect()->route('login');
	}
}
