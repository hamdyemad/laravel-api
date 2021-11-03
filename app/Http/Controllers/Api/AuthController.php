<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\Res;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{

    use Res;

    public function signup(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed']
        ]);
        if($validator->fails()) {
            return $this->sendRes('error', false, $validator->errors());
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
            if (! $token = auth()->login($user)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            return $this->respondWithToken($token);

        }
    }


    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email',Rule::exists('users', 'email')],
            'password' => ['required']
        ]);

        if($validator->fails()) {
            return $this->sendRes('error', false, $validator->errors());
        } else {
            $user = User::where('email', $request->email)->first();
            if($user) {
                if(Hash::check($request->password, $user->password)) {
                    if (! $token = auth()->login($user)) {
                        return response()->json(['error' => 'Unauthorized'], 401);
                    }
                    return $this->respondWithToken($token);

                } else {
                    return $this->sendRes('there is something error with this email or password', false, []);

                }
            }
        }
    }
}
