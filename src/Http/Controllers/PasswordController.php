<?php

namespace Heloufir\SimplePassport\Http\Controllers;

use App\Http\Controllers\Controller;
use Heloufir\SimplePassport\Rules\UserExists;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PasswordController extends Controller
{

    /**
     * Forgot password method
     *
     * @param Request $request
     *      The request object, containing the user's form data
     *
     * @return JsonResponse
     *
     * @author EL OUFIR Hatim <eloufirhatim@gmail.com>
     */
    public function forgot(Request $request): JsonResponse
    {
        $field = app(config('auth.providers.users.model'))->simplePassport ?: 'email';

        $rules = [
            $field => [
                'required',
                new UserExists
            ]
        ];
        if ($field === 'email') {
            array_push($rules[$field], 'email');
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['mail_sent' => false, 'errors' => collect($validator->getMessageBag())->flatten()->toArray()], 403);
        }
        $user = config('auth.providers.users.model')::where($field, $request->get($field))->first();
        $user->password_token = Str::random(100);
        $user->save();
        $this->sendForgotEmail($user);
        return response()->json(['mail_sent' => true, 'errors' => []], 200);
    }

    /**
     * Recover the password
     *
     * @param Request $request
     * @param string $token
     * @return JsonResponse
     */
    public function recover(Request $request, string $token): JsonResponse
    {
        $rules = [
            'password' => 'required|confirmed|min:6'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['password_recovered' => false, 'errors' => collect($validator->getMessageBag())->flatten()->toArray()], 403);
        }
        $user = config('auth.providers.users.model')::where('password_token', $token)->first();
        if ($user == null) {
            return response()->json(['password_recovered' => false, 'errors' => ['token' => trans('validation.exists', ['attribute' => 'token'])]], 403);
        }
        $user->password_token = null;
        $user->password = bcrypt($request->get('password'));
        $user->save();
        $this->sendRecoverEmail($user);
        return response()->json(['password_recovered' => true, 'errors' => []], 200);
    }

    /**
     * Send the forgotten password email to the user
     *
     * @param object $user
     *      The User model object
     *
     * @author EL OUFIR Hatim <eloufirhatim@gmail.com>
     */
    private function sendForgotEmail($user)
    {
        Mail::send('simple-passport.forgot-password', ['user' => $user], function ($mail) use ($user) {
            $mail->from(config('simple-passport.mail_from'), config('simple-passport.mail_from_name'));
            $mail->to($user->email);
            $mail->subject(trans('simple-passport::forgot-password.mail_subject'));
        });
    }

    /**
     * Send the recovered password email to the user
     *
     * @param object $user
     *      The User model object
     *
     * @author EL OUFIR Hatim <eloufirhatim@gmail.com>
     */
    private function sendRecoverEmail($user)
    {
        Mail::send('simple-passport.recover-password', ['user' => $user], function ($mail) use ($user) {
            $mail->from(config('simple-passport.mail_from'), config('simple-passport.mail_from_name'));
            $mail->to($user->email);
            $mail->subject(trans('simple-passport::recover-password.mail_subject'));
        });
    }

}
