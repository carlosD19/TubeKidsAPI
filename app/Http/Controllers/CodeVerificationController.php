<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Symfony\Component\HttpFoundation\Response;
use App\SendCode;

class CodeVerificationController extends Controller
{
    /**
     * Method to verify the code 2FA
     * @param request with code
     * @return if the code was varified or not
     */
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
    /**
     * Method to send the code
     * @param email to find the user
     * @return if the code was send
     */
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
    /**
     * Method to return failed response
     * @return response json error
     */
    public function failedResponseSMS()
    {
        return response()->json([
            'error' => 'Verify code is not correct, Please try again.'
        ], Response::HTTP_NOT_FOUND);
    }
    /**
     * Method to return success response
     * @return response json data
     */
    public function successResponseSMS()
    {
        return response()->json([
            'data' => 'Your account is actived.'
        ], Response::HTTP_OK);
    }
}
