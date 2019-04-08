<?php

namespace Heloufir\SimplePassport\Http\Requests;

use Heloufir\SimplePassport\Rules\UserExists;
use Illuminate\Foundation\Http\FormRequest;

class RecoverPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required', new UserExists],
            'password' => 'required'
        ];
    }

    /**
     * Validation of the the request and attach user to it
     *
     */
    public function validateResolved()
    {
        parent::validateResolved();

        $model = config('simple-passport.model');
        $this->request->add([
            'user_asked' => $model::where($model::getEmailField(), '=', $this->request->get('email'))->first()
        ]);
    }
}
