<?php

namespace Heloufir\SimplePassport\Rules;

use Illuminate\Contracts\Validation\Rule;

class UserExists implements Rule
{
    protected $model;

    public function __construct()
    {
        $this->model = config('simple-passport.model');
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->model::where($this->model::getEmailField(), '=', $value)->count() !== 0;
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
