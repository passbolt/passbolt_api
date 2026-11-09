<?php
declare(strict_types=1);

namespace Passbolt\BrowserAuth\Service;

use App\Model\Entity\AuthenticationToken;
use App\Service\AuthenticationTokens\AuthenticationTokenConsumeService;
use App\Service\AuthenticationTokens\AuthenticationTokenGetService;
use Cake\Http\Exception\ForbiddenException;
use Cake\ORM\Locator\LocatorAwareTrait;

class BrowserGrantService
{
    use LocatorAwareTrait;

    public const TYPE = 'browser_auth';
    public const LIFETIME = '60 seconds';

    /**
     * Create browser authorization grant token.
     *
     * @param string $userId User ID
     * @param array $binding Binding data
     * @param string|null $mfaToken MFA token
     * @return \App\Model\Entity\AuthenticationToken
     */
    public function create(string $userId, array $binding, ?string $mfaToken): AuthenticationToken
    {
        return $this->fetchTable('AuthenticationTokens')->generate($userId, self::TYPE, null, [
            'challenge' => $binding['challenge'],
            'fingerprint' => $binding['fingerprint'],
            // Revalidated on exchange: revocation between approval and exchange must take effect.
            'mfa_token' => $mfaToken,
        ]);
    }

    /**
     * Inspect grant token and match against verifier.
     *
     * @param string $token Token
     * @param string $userId User ID
     * @param string $verifier Verifier
     * @return array
     */
    public function inspect(string $token, string $userId, string $verifier): array
    {
        $grant = (new AuthenticationTokenGetService())->getActiveNotExpiredOrFail(
            $token,
            $userId,
            self::TYPE,
            self::LIFETIME
        );
        $data = $grant->getJsonDecodedData();
        if (!BrowserGrantBinding::matches($data['challenge'] ?? '', $verifier)) {
            throw new ForbiddenException('Invalid browser authorization.');
        }
        $user = $this->fetchTable('Users')->find()->contain(['Roles', 'Gpgkeys'])
            ->where(['Users.id' => $userId, 'Users.active' => true, 'Users.deleted' => false])->first();
        if (!$user || !hash_equals($data['fingerprint'] ?? '', strtoupper($user->gpgkey->fingerprint))) {
            throw new ForbiddenException('Invalid browser authorization.');
        }
        if ($user->isDisabled()) {
            throw new ForbiddenException('Invalid browser authorization.');
        }

        return [$user, $data];
    }

    /**
     * Consume grant token.
     *
     * @param string $token Token
     * @param string $userId User ID
     * @return void
     */
    public function consume(string $token, string $userId): void
    {
        (new AuthenticationTokenConsumeService())->consumeActiveNotExpiredOrFail(
            $token,
            $userId,
            self::TYPE,
            self::LIFETIME
        );
    }
}
