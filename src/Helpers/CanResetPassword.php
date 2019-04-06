<?php


namespace Heloufir\SimplePassport\Helpers;


use Heloufir\SimplePassport\Token;
use Illuminate\Support\Str;

trait CanResetPassword
{

    public function simpleTokens()
    {
        return $this->hasOne(Token::class);
    }

    /**
     * Get the email field
     *
     * @return string
     */
    protected static function getEmailField(): string
    {
        return 'email';
    }


    /**
     * Get the password field
     *
     * @return string
     */
    protected function getPasswordField(): string
    {
        return 'password';
    }

    public function generateResetPassword()
    {
        return $this->simpleTokens()->save(
            new Token([
                'token' => 'random'
            ])
        );
    }

    public function getResetPasswordToken()
    {
        return $this->simpleTokens->token;
    }

    /**
     * Set the new password
     *
     * @param $password
     * @return $this
     */
    public function setNewPassword($password)
    {
        $this->{$this->getPasswordField()} = bcrypt($password);

        $this->save();

        return $this;
    }

    public function forgotToken()
    {
        $this->simpleTokens()->delete();
    }

    /**
     * Token Handler
     *
     * @param $token
     * @return TokenHandler
     */
    public function simpleToken($token)
    {
        return new TokenHandler($token, $this);
    }
}
