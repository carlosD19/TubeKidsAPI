<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\User;
use App\SendCode;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'signup']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse response json
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Email or password invalid.'], 401);
        }
        //if ($this->sendCode(request(['email']))) {
            return $this->respondWithToken($token);
        //}
        return response()->json(['error' => 'Cannot send the code, try again.'], 409);
    }

    /**
     * Send code from nexmo
     * @param email to get the user
     * @return true or false if code was send
     */
    public function sendCode($email)
    {
        $user = User::where('email', $email)->first();
        if ($user->email_verified_at) {
            $user->code        = SendCode::sendCode($user->phone_number);
            $user->active_code = false;
            if ($user->save()) {
                return true;
            }
            return false;
        }
        return true;
    }

    /**
     * In this method a new user is registered
     *
     * @param  \Illuminate\Http\Request $request receive user datas
     * @return \Illuminate\Http\Response the json with user token
     */
    public function signup(UserRequest $request)
    {
        User::create($request->all());
        $credentials = request(['email', 'password']);
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Email or password invalid.'], 401);
        }
        return $this->respondWithToken($token);
    }



    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $user = auth()->user();
        $user = User::find($user->id);
        $user->active_code = false;
        $user->save();
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out.']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 600,
            'user'         => auth()->user()
        ]);
    }
}
