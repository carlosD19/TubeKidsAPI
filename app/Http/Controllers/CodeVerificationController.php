<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Symfony\Component\HttpFoundation\Response;

class CodeVerificationController extends Controller
{
    public function verifySMS(Request $request)
    {
        if ($user = User::where('code', $request->code)->first()) {
            $user->active_code = true;
            $user->code        = null;
            $user->save();
            return $this->successResponseSMS();
        }
        return $this->failedResponseSMS();
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
