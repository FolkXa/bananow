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
    public function register(RegisterRequest $request){
           $user = User::create($request->validated());
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

    public function getContacts(string $user_id) {
        return User::find($user_id)->contacts;
    }

    public function updateContacts(Request $request, string $user_id) {
        $request->validate([
            'contacts' => 'required'
        ]);
        $user = User::find($user_id);
        $user->contacts = $request->get('contacts');
        $user->save();
        return response()->json(['message' => 'update success']);

    }
    public function logout(Request $request) {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'logout success'],200);
    }
}
