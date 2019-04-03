<?php


namespace Heloufir\SimplePassport\helpers;


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
}
