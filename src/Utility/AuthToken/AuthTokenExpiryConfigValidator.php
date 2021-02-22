<?php
declare(strict_types=1);

namespace App\Utility\AuthToken;

class AuthTokenExpiryConfigValidator
{
    /**
     * @param string $expiry Expiry
     * @return string|null
     */
    public function __invoke(string $expiry)
    {
        if (preg_match('/^[0-9]+ (year|month|day|hour|minute|second)/', $expiry)) {
            return $expiry;
        }

        return null;
    }
}
