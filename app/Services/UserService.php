<?php

namespace App\Services;

use App\User;
use ActiveCampaign;
use App\SocialProvider;
use Illuminate\Auth\Events\Registered;

class UserService
{
    /**
     * Find a User by their social media handle.
     * 
     * @param  object $user
     * @param  string $provider
     * @return App\User
     */
    public function handleSocialLogin($socialUser, $provider)
    {
        // If the user is logging in with a social account they've used before
        // we can let them in.
        $socialLogin = SocialProvider::where(function ($q) use ($socialUser, $provider) {
            $q->where('provider', $provider)
                ->where('provider_id', $socialUser->id);
        })
        ->first();

        if (!is_null($socialLogin)) {
            return $socialLogin->user;
        }


        // The user may be logging in through a different social account.
        // We still want them to have access to all of their contacts. Add
        // the new social account and return the user.
        $emailLogin = User::where('email', $socialUser->email)->first();

        if (!is_null($emailLogin)) {
            $socialProvider = new SocialProvider([
                'provider'      => $provider,
                'provider_id'   => $socialUser->id
            ]);

            $emailLogin->SocialProvider()->save($socialProvider);
            return $emailLogin;
        };


        // If nothing was found then create a new user and the social account
        // they logged in from.
        $user = User::create([
            'name'          => $socialUser->name,
            'email'         => $socialUser->email,
            'password'      => bcrypt(str_random(64)) // Create some random password
        ]);

        event(new Registered($user));

        $socialProvider = new SocialProvider([
            'provider'      => $provider,
            'provider_id'   => $socialUser->id
        ]);

        $user->SocialProvider()->save($socialProvider);

        return $user;
    }
}
