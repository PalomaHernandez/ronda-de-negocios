<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class LoginController extends Controller
{

	public function index()
	{
		return view('auth.login');
	}

	public function login(Request $request)
	{
		$this->validateLogin();

		if (!Auth::attempt($this->credentials())) {
			if(request()->expectsJson()){
				return response()->json(['message' => 'Invalid credentials'], 401);
			}
			return redirect()->back()->with('error', 'Credenciales inválidas.');
		}

		$user = Auth::user()->load('images');
		Log::info('User logged in', ['user' => $user]);

		$token = $user->createToken('auth_token')->plainTextToken;

		if(request()->expectsJson()){
			return response()->json([
				'user' => $user,
				'role' => $user->roles->pluck('name'),
				'token' => $token,
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
