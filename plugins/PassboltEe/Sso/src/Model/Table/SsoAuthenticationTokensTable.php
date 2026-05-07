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
 * @since         3.9.0
 */
namespace Passbolt\Sso\Model\Table;

use App\Error\Exception\ValidationException;
use App\Model\Entity\AuthenticationToken;
use App\Model\Table\AuthenticationTokensTable;
use App\Utility\AuthToken\AuthTokenExpiry;
use App\Utility\Validation\UserAgentValidation;
use Cake\Core\Configure;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validation;
use Passbolt\Sso\Model\Entity\SsoAuthenticationToken;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Model\Rule\IsStateUserActiveRule;
use Passbolt\Sso\Utility\AuthToken\SsoAuthTokenExpiry;

/**
 * SsoAuthenticationTokens Model
 */
class SsoAuthenticationTokensTable extends AuthenticationTokensTable
{
    public const SSO_ALLOWED_TYPES = [
        SsoState::TYPE_SSO_SET_SETTINGS,
        SsoState::TYPE_SSO_GET_KEY,
        SsoState::TYPE_SSO_RECOVER,
    ];

    /**
     * @return array self::SSO_ALLOWED_TYPES
     */
    public function getAllowedTypes(): array
    {
        return self::SSO_ALLOWED_TYPES;
    }

    /**
     * @return \App\Utility\AuthToken\AuthTokenExpiry
     */
    public function tokenExpiryFactory(): AuthTokenExpiry
    {
        return new SsoAuthTokenExpiry();
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules = parent::buildRules($rules);

        $rules->addCreate(new IsStateUserActiveRule(), 'user_is_active', [
            'table' => 'Users',
            'errorField' => 'user_id',
            'message' => __('The user is not active.'),
        ]);

        return $rules;
    }

    /**
     * Build the SSO authentication token
     *
     * @param string $userId uuid
     * @param string $type AuthenticationToken::TYPE_*
     * @param ?string $token token value (optional)
     * @param ?array $data data value (optional)
     * @throws \App\Error\Exception\ValidationException is the user is not valid
     * @return \App\Model\Entity\AuthenticationToken $token
     */
    public function generate(
        string $userId,
        string $type,
        ?string $token = null,
        ?array $data = []
    ): AuthenticationToken {
        // TODO SsoAuthenticationTokenDataForm
        $msg = __('It is not possible to create an authentication token for this user.');
        if (!isset($data[SsoAuthenticationToken::DATA_IP]) || !isset($data[SsoAuthenticationToken::DATA_USER_AGENT])) {
            throw new ValidationException($msg);
        }
        if (
            !is_string($data[SsoAuthenticationToken::DATA_IP]) ||
            !is_string($data[SsoAuthenticationToken::DATA_USER_AGENT])
        ) {
            throw new ValidationException($msg);
        }
        if (!Validation::ip($data[SsoAuthenticationToken::DATA_IP])) {
            throw new ValidationException($msg);
        }
        if (!UserAgentValidation::isValid($data[SsoAuthenticationToken::DATA_USER_AGENT])) {
            throw new ValidationException($msg);
        }
        if (
            !isset($data[SsoAuthenticationToken::DATA_SSO_SETTING_ID])
            || !is_string($data[SsoAuthenticationToken::DATA_SSO_SETTING_ID])
            || !Validation::uuid($data[SsoAuthenticationToken::DATA_SSO_SETTING_ID])
        ) {
            throw new ValidationException($msg);
        }

        $tokenData = [
            SsoAuthenticationToken::DATA_SSO_SETTING_ID => $data[SsoAuthenticationToken::DATA_SSO_SETTING_ID],
        ];
        if (Configure::read('passbolt.security.userIp')) {
            $tokenData[SsoAuthenticationToken::DATA_IP] = $data[SsoAuthenticationToken::DATA_IP];
        }
        if (Configure::read('passbolt.security.userAgent')) {
            $tokenData[SsoAuthenticationToken::DATA_USER_AGENT] = $data[SsoAuthenticationToken::DATA_USER_AGENT];
        }

        return parent::generate($userId, $type, $token, $tokenData);
    }
}
