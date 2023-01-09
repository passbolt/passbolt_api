<?php
declare(strict_types=1);

namespace App\Utility\AuthToken;

class AuthTokenExpiryConfigValidator
{
    /**
     * @param string|null $expiry Expiry
     * @return string|null
     */
    public function __invoke(?string $expiry = null)
    {
        if (is_null($expiry)) {
            return null;
        }

        if (preg_match('/^[0-9]+ (year|month|day|hour|minute|second)/', $expiry)) {
            return $expiry;
        }

        return null;
    }
}
