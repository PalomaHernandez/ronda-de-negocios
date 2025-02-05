<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller {

	public function index(){
		return view('auth.login');
	}

	public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (!Auth::attempt($credentials)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $user = Auth::user();
    return response()->json([
        'user' => $user,
    	'role' => $user->roles->pluck('name'),
    ]);
}

	public function attempt(){
		$this->validateLogin();
        
		if($this->attemptLogin()){
			return $this->sendLoginResponse();
		}

		if(request()->expectsJson()){
			return response()->json([
				'res' => false,
				'text' => 'No se ha podido iniciar sesión.',
			]);
		}
		return back()->withErrors([
			'email' => 'Los datos ingresados son incorrectos.',
		]);
	}

	protected function validateLogin():void{
		request()->validate([
			'email'    => 'required|email',
			'password' => 'required',
		]);
	}

	protected function attemptLogin():bool{
		return auth()->attempt($this->credentials());
	}

	protected function credentials():array{
		return request()->only('email', 'password');
	}

	protected function sendLoginResponse(){
        Log::error('Session middleware is active:', ['hasSession' => request()->hasSession()]);
        Log::error('Authenticated User:', ['user' => auth()->user()]);
		request()->session()->regenerate();
		if(request()->expectsJson()){
			return response()->json([
				'res' => true,
				'text' => 'Inicio de sesión exitoso',
				'user' => request()->user(),
				'roles' => request()->user()->roles->pluck('name')
			]);
		}
		return redirect()->route('home');
	}

	public function logout(){
		auth()->logout();
		request()->session()->invalidate();
		request()->session()->regenerateToken();
		if($response = $this->loggedOut()){
			return $response;
		}
		abort(500);
	}

	protected function loggedOut(){
		if(request()->expectsJson()){
			return response()->json([
				'res' => true,
				'text' => 'Cierre de sesión exitoso',
			]);
		}
		return redirect()->route('login');
	}

}
