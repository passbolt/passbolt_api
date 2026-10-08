<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         5.17.0
 */
namespace Passbolt\MultiFactorAuthentication\Service\Webauthn;

use App\Model\Entity\AuthenticationToken;
use App\Service\AuthenticationTokens\AuthenticationTokenConsumeService;
use App\Utility\UserAccessControl;
use Cake\Http\Exception\BadRequestException;
use Cake\ORM\Locator\LocatorAwareTrait;
use InvalidArgumentException;

class MfaWebauthnChallengeTokenService
{
    use LocatorAwareTrait;

    private const ALLOWED_TYPES = [
        AuthenticationToken::TYPE_MFA_SETUP,
        AuthenticationToken::TYPE_MFA_VERIFY,
    ];

    /**
     * @param \App\Utility\UserAccessControl $uac UAC.
     * @param string $tokenType TYPE_MFA_SETUP or TYPE_MFA_VERIFY.
     * @param string $optionsJson JSON-encoded options.
     * @return \App\Model\Entity\AuthenticationToken
     */
    public function create(
        UserAccessControl $uac,
        string $tokenType,
        string $optionsJson,
    ): AuthenticationToken {
        $this->assertType($tokenType);
        /** @var \App\Model\Table\AuthenticationTokensTable $authenticationTokens */
        $authenticationTokens = $this->fetchTable('AuthenticationTokens');

        return $authenticationTokens->generate(
            $uac->getId(),
            $tokenType,
            null,
            ['options' => $optionsJson],
        );
    }

    /**
     * @param \App\Utility\UserAccessControl $uac UAC.
     * @param string $tokenType TYPE_MFA_SETUP or TYPE_MFA_VERIFY.
     * @param string $handle Ceremony handle.
     * @return string Stored options JSON.
     */
    public function consume(
        UserAccessControl $uac,
        string $tokenType,
        string $handle,
    ): string {
        $this->assertType($tokenType);
        $token = (new AuthenticationTokenConsumeService())
            ->consumeActiveNotExpiredOrFail($handle, $uac->getId(), $tokenType);
        $raw = $token->data ?? '';
        $data = $raw === '' ? null : json_decode($raw, true);
        if (!is_array($data) || !isset($data['options']) || !is_string($data['options'])) {
            throw new BadRequestException(__('The ceremony options are missing or corrupted.'));
        }

        return $data['options'];
    }

    /**
     * @param string $tokenType Token type.
     * @return void
     */
    private function assertType(string $tokenType): void
    {
        if (!in_array($tokenType, self::ALLOWED_TYPES, true)) {
            throw new InvalidArgumentException(sprintf('Unsupported ceremony token type: %s', $tokenType));
        }
    }
}
