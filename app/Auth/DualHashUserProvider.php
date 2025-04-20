<?php

namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;

class DualHashUserProvider extends EloquentUserProvider
{
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        $plain = $credentials['password'];
        $hashed = $user->getAuthPassword();

        // Check if it's an MD5 hash (typically 32 characters)
        if (strlen($hashed) == 32) {
            // Verify using MD5
            $passed = md5($plain) === $hashed;
            
            // If successful, rehash with Laravel's method
            if ($passed) {
                $user->password = Hash::make($plain);
                $user->save();
            }
            
            return $passed;
        }

        // Otherwise use Laravel's built-in verification
        return Hash::check($plain, $hashed);
    }
}