<?php

namespace Heloufir\SimplePassport\Rules;

use Illuminate\Contracts\Validation\Rule;

class UserExists implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return config('auth.providers.users.model')::where(
            app(config('auth.providers.users.model'))->simplePassport ?: 'email',
            $value
        )->count() != 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.exists', ['attribute' => 'user']);
    }
}
