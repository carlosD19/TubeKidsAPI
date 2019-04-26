<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyMail;

class VerificationController extends Controller
{
    /**
     * Method to get the request and redirect to send mail
     * @param request with email and token
     * @return if send mail was success or not
     */
    public function sendEmail(Request $request)
    {
        if (!$this->validateEmail($request->email)) {
            return $this->failedResponse();
        }
        $this->send($request->email, $request->bearerToken());
        return $this->successResponse();
    }
    /**
     * Method to send mail
     * @param email and token
     */
    public function send($email, $token)
    {
        $user = User::where('email', $email)->first();
        Mail::to($email)->send(new VerifyMail($token, $user->firstname));
    }
    /**
     * Method to validate if the email is correct
     * @param email
     * @return if the email is correct
     */
    public function validateEmail($email)
    {
        return !!User::where('email', $email)->first();
    }
    /**
     * Method to confirm email
     * @param token of the user
     * @return success message
     */
    public function confirmEmail($token)
    {
        $user = auth()->user();
        $user = User::find($user->id);
        if (!$user->email_verified_at) {
            $user->email_verified_at = date("Y-m-d");
            $user->save();
        }
        return 'Su correo ha sido verificado.';
    }
    /**
     * Method to return a error
     * @return error HTTP NOT FOUND
     */
    public function failedResponse()
    {
        return response()->json([
            'error' => 'Email does\´t found on our database'
        ], Response::HTTP_NOT_FOUND);
    }
    /**
     * Method to return a success response
     * @return success HTTP OK
     */
    public function successResponse()
    {
        return response()->json([
            'data' => 'Verify email is send successfully, please check your inbox.'
        ], Response::HTTP_OK);
    }
}
