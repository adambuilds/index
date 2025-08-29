<?php

namespace App\Auth;

use App\Models\User;
use Auth0\Laravel\UserRepositoryAbstract;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Maps Auth0 users to local Eloquent users.
 */
class Auth0UserRepository extends UserRepositoryAbstract
{
    /**
     * Build a stateless user for API access tokens.
     * For APIs, you typically don’t need a local user; return null
     * to fall back to the package’s stateless user handling.
     */
    public function fromAccessToken(array $user): ?Authenticatable
    {
        // If you want to map API tokens to local users, implement similar to fromSession.
        return null;
    }

    /**
     * Build or retrieve a local user for session logins.
     */
    public function fromSession(array $profile): ?Authenticatable
    {
        $email = $profile['email'] ?? null;
        $name = $profile['name'] ?? ($profile['nickname'] ?? 'User');

        if (! $email) {
            // Require email for application access
            return null;
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            $user = new User();
            $user->name = $name;
            $user->email = $email;
            $user->password = Hash::make(Str::random(40));
        } else {
            // Keep name in sync if it changed
            if ($name && $user->name !== $name) {
                $user->name = $name;
            }
        }

        // Trust Auth0's email verification flag when present
        if (array_key_exists('email_verified', $profile) && $profile['email_verified']) {
            if (! $user->email_verified_at) {
                $user->email_verified_at = now();
            }
        }

        $user->save();

        return $user;
    }
}

