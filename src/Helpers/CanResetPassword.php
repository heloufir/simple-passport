<?php


namespace Heloufir\SimplePassport\Helpers;


use Heloufir\SimplePassport\Token;
use Illuminate\Support\Str;

trait CanResetPassword
{

    /**
     * Token instance
     *
     * @return mixed
     */
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

    /**
     * Get the generated token
     *
     * @return mixed
     */
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

    /**
     * Delete the token after setting up the new password
     *
     */
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
    public function simpleToken($token = null)
    {
        Token::where('user_id', $this->id)->delete();
        if(is_null($token)){
            return new TokenHandler(Str::random(40), $this);
        }

        return new TokenHandler($token, $this);
    }
}
