<?php

namespace AppBundle\Services;

use AppBundle\Entity\User;

/**
 * Class UserService
 * Service used to process data for User entities
 */
class UserService
{
    public function generateUsername(User $user)
    {
        return sprintf(
            '%s.%s',
            strtolower($user->getFirstName()),
            strtolower($user->getLastName())
        );
    }

    public function randomPassword($length = 8)
    {
        if (function_exists('random_bytes')) {
            return bin2hex(random_bytes($length));
        }

        if (function_exists('openssl_random_pseudo_bytes')) {
            return bin2hex(openssl_random_pseudo_bytes($length));
        }
    }
}
