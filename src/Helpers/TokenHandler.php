<?php


namespace Heloufir\SimplePassport\Helpers;



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
}
