<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyMail;

class VerificationController extends Controller
{
    public function sendEmail(Request $request)
    {
        if (!$this->validateEmail($request->email)) {
            return $this->failedResponse();
        }
        $this->send($request->email, $request->bearerToken());
        return $this->successResponse();
    }

    public function send($email, $token)
    {
        $user = User::where('email', $email)->first();
        Mail::to($email)->send(new VerifyMail($token, $user->firstname));
    }

    public function validateEmail($email)
    {
        return !!User::where('email', $email)->first();
    }

    public function confirmEmail($token)
    {
        $user = auth()->user();
        $user = User::find($user->id);
        $user->email_verified_at = date("Y-m-d");
        $user->save();
        return $user;
    }

    public function failedResponse()
    {
        return response()->json([
            'error' => 'Email does\´t found on our database'
        ], Response::HTTP_NOT_FOUND);
    }

    public function successResponse()
    {
        return response()->json([
            'data' => 'Verify email is send successfully, please check your inbox.'
        ], Response::HTTP_OK);
    }
}
