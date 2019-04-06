<?php

namespace Heloufir\SimplePassport\Http\Controllers;

use Heloufir\SimplePassport\Http\Requests\RecoverPasswordRequest;
use Heloufir\SimplePassport\Http\Requests\ResetPasswordRequest;
use Heloufir\SimplePassport\Jobs\SendRecoveredPasswordJob;
use Heloufir\SimplePassport\Jobs\SendResetPasswordTokenJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;

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
     * @param RecoverPasswordRequest $request
     * @param string $token
     * @return JsonResponse
     */
    public function recover(RecoverPasswordRequest $request, string $token): JsonResponse
    {
        $user = $request->user_asked;

        if(! $user->simpleToken($token)->belongs()){
            return response()->json([
                'password_recovered' => false,
                'error' => 'Token incorrect'
            ], 401);
        }

        $user->setNewPassword($request->get('password'))
             ->forgotToken();

        SendRecoveredPasswordJob::dispatch(
            $user
        );
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
