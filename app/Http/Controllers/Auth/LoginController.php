<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Hash;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginRequest $request)
    {
        $user = User::where([
            "email" => $request->email
        ])->first();


        if (!$user) {
            return response()->json([
                'message' => 'Invalid email/password combination'
            ]);
        }

        if(!Hash::check($request->password, $user->password)){
            return response()->json([
                'message' => 'Invalid email/password combination'
            ]);
        }

        $token = $user->createToken('auth')->plainTextToken;

        //1|UeSEnhIxZCZ1RJb4dADEiQm1ipVvzOp70ytL0xW80f286376
        return response()->json(['token' => $token]);

    }
}
