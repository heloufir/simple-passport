<?php


namespace Heloufir\SimplePassport\Helpers;


use Illuminate\Support\Str;

trait CanResetPassword
{

    /**
     * Get the email field
     *
     * @return string
     */
    public static function getEmailField(): string
    {
        return 'email';
    }

    public function generateResetPassword()
    {
        //var_dump(Str::random(100));
    }

    public function getResetPasswordToken()
    {
        return 'token';
    }
}
