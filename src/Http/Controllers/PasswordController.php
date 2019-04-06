<?php

namespace Heloufir\SimplePassport\Http\Controllers;

use Heloufir\SimplePassport\Http\Requests\RecoverPasswordRequest;
use Heloufir\SimplePassport\Http\Requests\ResetPasswordRequest;
use Heloufir\SimplePassport\Jobs\SendRecoveredPasswordJob;
use Heloufir\SimplePassport\Jobs\SendResetPasswordTokenJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class PasswordController extends Controller
{
    /**
     * Forgot password method
     *
     * @param ResetPasswordRequest $request
     * @return JsonResponse
     */
    public function forgot(ResetPasswordRequest $request): JsonResponse
    {
        $user = $this->getRelatedUser($request);

        $user->simpleToken()->generateResetPassword();

        // Can be queued

        $this->dispatchJobWithDelay(
            SendResetPasswordTokenJob::class, $user
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
        $user = $this->getRelatedUser($request);;

        if(! $user->simpleToken($token)->belongs()){
            return response()->json([
                'password_recovered' => false,
                'error' => 'Token incorrect'
            ], 401);
        }

        $user->setNewPassword($request->get('password'))
             ->forgotToken();

        // Can be queued

        $this->dispatchJobWithDelay(
            SendRecoveredPasswordJob::class, $user
        );

        return response()->json(['password_recovered' => true, 'errors' => []], 200);
    }


    /**
     * Get the related user
     *
     * @param $request
     * @return mixed
     */
    protected function getRelatedUser($request)
    {
        return $request->user_asked;
    }

    /**
     * @param $class
     * @param $user
     * @return mixed
     */
    protected function dispatchJobWithDelay($class, $user)
    {
        return $class::dispatch(
            $user
        )->delay(
            now()->addSecond(config('simple-passport.after_seconds'))
        );
    }
}
