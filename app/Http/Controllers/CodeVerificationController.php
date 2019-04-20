<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Symfony\Component\HttpFoundation\Response;
use App\SendCode;

class CodeVerificationController extends Controller
{
    public function verifySMS(Request $request)
    {
        if ($user = User::where('code', $request->code)->where('email', $request->email)->first()) {
            $user->active_code = true;
            $user->code        = null;
            $user->save();
            return $this->successResponseSMS();
        }
        return $this->failedResponseSMS();
    }

    public function sendCode($email)
    {
        $user              = User::where('email', $email)->first();
        $user->code        = SendCode::sendCode($user->phone_number);
        $user->active_code = false;
        if ($user->save()) {
            return response()->json([
                'data' => 'Your code has been sending'
            ], Response::HTTP_OK);
        }
        return response()->json([
            'error' => 'Your code was not sending, Please try again.'
        ], Response::HTTP_NOT_FOUND);
    }

    public function failedResponseSMS()
    {
        return response()->json([
            'error' => 'Verify code is not correct, Please try again.'
        ], Response::HTTP_NOT_FOUND);
    }

    public function successResponseSMS()
    {
        return response()->json([
            'data' => 'Your account is actived.'
        ], Response::HTTP_OK);
    }
}
