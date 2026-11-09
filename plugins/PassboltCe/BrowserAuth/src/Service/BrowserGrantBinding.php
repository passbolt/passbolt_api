<?php
declare(strict_types=1);

namespace Passbolt\BrowserAuth\Service;

use InvalidArgumentException;

/** Validate the public binding; the verifier never leaves the requesting agent. */
class BrowserGrantBinding
{
    /**
     * Validate binding parameters.
     *
     * @param array $data Input data
     * @return array
     */
    public static function validate(array $data): array
    {
        $challenge = $data['challenge'] ?? null;
        $fingerprint = $data['fingerprint'] ?? null;
        if (
            !is_string($challenge) || !preg_match('/^[A-Za-z0-9_-]{43}$/D', $challenge) ||
            !is_string($fingerprint) || !preg_match('/^(?:[A-F0-9]{40}|[A-F0-9]{64})$/D', $fingerprint)
        ) {
            throw new InvalidArgumentException('Invalid browser authorization binding.');
        }

        return compact('challenge', 'fingerprint');
    }

    /**
     * Check if verifier matches challenge.
     *
     * @param string $challenge Challenge
     * @param string $verifier Verifier
     * @return bool
     */
    public static function matches(string $challenge, string $verifier): bool
    {
        if (!preg_match('/^[A-Za-z0-9_-]{43}$/D', $verifier)) {
            return false;
        }
        $actual = rtrim(strtr(base64_encode(hash('sha256', $verifier, true)), '+/', '-_'), '=');

        return hash_equals($challenge, $actual);
    }
}
