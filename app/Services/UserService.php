<?php

namespace App\Services;

use App\User;

class UserService
{
    /**
     * Find a user by the passed in $provider and social site's id OR
     * email. Return that user if found. If not create a new user
     * and return it.
     * @param  object $user
     * @param  string $provider
     * @return App\User
     */
    public function handleSocialLogin($user, $provider)
    {
        $authUser = User::where(function ($q) use ($user, $provider) {
                $q->where('provider', $provider)
                    ->where('provider_id', $user->id);
            })
            ->orWhere('email', $user->email)
            ->first();

        if ($authUser) {
            return $authUser;
        }

        return User::create([
            'name'          => $user->name,
            'email'         => $user->email,
            'password'      => bcrypt(str_random(64)),
            'provider'      => $provider,
            'provider_id'   => $user->id
        ]);
    }
}
