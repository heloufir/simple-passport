<?php


namespace Heloufir\SimplePassport\Helpers;



use Heloufir\SimplePassport\Token;

class TokenHandler
{
    protected $token;

    protected $user;

    public function __construct($token, $user)
    {
        $this->token = $token;
        $this->user = $user;
    }

    /**
     * Check if the token belongs to the user
     *
     * @return bool
     */
    public function belongs()
    {
        return $this->user->simpleTokens->token === $this->token;
    }

    /**
     * Generate the password token
     *
     * @return mixed
     */
    public function generateResetPassword()
    {
        return $this->user->simpleTokens()->save(
            new Token([
                'token' => $this->token
            ])
        );
    }
}
