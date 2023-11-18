<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Auth\AuthResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(LoginRequest $request){

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        return $this->makeToken($user);
    }
    public function register(Request $request){
        $request->validate([
            'username' => 'required|string|unique:users|min:8|max:20',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8|max:20',
            'phone' => 'required|unique:users|min:10|max:15',
            'role' => 'nullable|in:admin,chef,staff,customer',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
        ]);
        $user = User::create($request->all());
        $user->lastname = $request->get('lastname');
        $user->phone = $request->get('phone');
        $user->save();
        $user->refresh();
        return $this->makeToken($user);

    }
    public function makeToken($user){
        $token =  $user->createToken('myToken')->plainTextToken;
        return AuthResource::make([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'phone' => $user->phone,
                'imgPath' => $user->imgPath
            ]
            ]);
    }
    public function logout(Request $request) {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'logout success'],200);
    }
}
