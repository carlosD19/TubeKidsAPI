<?php

namespace App;

class SendCode
{
    public static function sendCode($phone)
    {
        $code  = rand(1111, 9999);
        $nexmo = app('Nexmo\Client');
        $nexmo->message()->send([
            'to'   => '+506'. (int) $phone,
            'from' => 'Nexmo',
            'text' => 'Verify code: ' . $code,
        ]);
        return $code;
    }
}
