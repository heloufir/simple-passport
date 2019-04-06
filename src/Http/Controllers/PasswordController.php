<?php

namespace Heloufir\SimplePassport\Http\Controllers;

use App\Http\Controllers\Controller;
use Heloufir\SimplePassport\Http\Requests\ResetPasswordRequest;
use Heloufir\SimplePassport\Jobs\SendResetPasswordTokenJob;
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
     * @param ResetPasswordRequest $request
     *      The request object, containing the user's form data
     *
     * @return JsonResponse
     *
     * @author EL OUFIR Hatim <eloufirhatim@gmail.com>
     * @modified By INANI El Houssain <inanielhoussain@gmail.com>
     */
    public function forgot(ResetPasswordRequest $request): JsonResponse
    {
        $user = $request->user_asked;

        $user->generateResetPassword();

        // Can be queued
        SendResetPasswordTokenJob::dispatch(
            $user
        );

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
