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
 * @since         3.6.0
 */
namespace Passbolt\AccountRecovery\Test\Lib;

use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\User;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryResponse;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPolicyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPublicKeyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryPrivateKeyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryPrivateKeyPasswordFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryRequestFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryResponseFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryUserSettingFactory;

class AccountRecoveryRequestScenario
{
    // SCENARIO

    public static function startContinueScenarioApproved(): array
    {
        self::startPolicy();
        $user = self::startUser();
        $token = self::startToken($user);
        $request = self::startRequestApproved($user, $token);

        return [$request, $user, $token];
    }

    public static function startContinueScenarioPending(): array
    {
        self::startPolicy();
        $user = self::startUser();
        $token = self::startToken($user);
        $request = AccountRecoveryRequestFactory::make()
            ->pending()
            ->withUser($user->id)
            ->setField('authentication_token_id', $token->id)
            ->persist();

        return [$request, $user, $token];
    }

    public static function startContinueScenarioRejected(): array
    {
        self::startPolicy();
        $user = self::startUser();
        $token = self::startToken($user);
        $request = AccountRecoveryRequestFactory::make()
            ->rejected()
            ->withUser($user->id)
            ->setField('authentication_token_id', $token->id)
            ->persist();

        return [$request, $user, $token];
    }

    // HELPERS

    public static function startUser(): User
    {
        /** @var User $user */
        $user = UserFactory::make()
            ->setField('id', UuidFactory::uuid('user.id.ada'))
            ->active()
            ->user()
            ->with('Gpgkeys', GpgkeyFactory::make()->patchData([
                'fingerprint' => '03F60E958F4CB29723ACDF761353B5B15D9B054F',
                'armored_key' => file_get_contents(FIXTURES . 'Gpgkeys' . DS . 'ada_public.key'),
            ]))
            ->persist();

        self::startUserSettingsApproved($user);

        return $user;
    }

    public static function startUserDeleted(): User
    {
        /** @var User $user */
        $user = UserFactory::make()
            ->setField('id', UuidFactory::uuid('user.id.ada'))
            ->active()
            ->deleted()
            ->user()
            ->with('Gpgkeys', GpgkeyFactory::make()->patchData([
                'fingerprint' => '03F60E958F4CB29723ACDF761353B5B15D9B054F',
                'armored_key' => file_get_contents(FIXTURES . 'Gpgkeys' . DS . 'ada_public.key'),
            ]))
            ->persist();

        self::startUserSettingsApproved($user);

        return $user;
    }

    public static function startUserSettingsApproved(User $user): array
    {
        $settings = AccountRecoveryUserSettingFactory::make()
            ->approved()
            ->setField('user_id', $user->id)
            ->persist();

        $privateKey = AccountRecoveryPrivateKeyFactory::make()
            ->setField('id', UuidFactory::uuid('acr.private_key.ada.id'))
            ->setField('user_id', $user->id)
            ->persist();

        $passwords = AccountRecoveryPrivateKeyPasswordFactory::make()
            ->setField('private_key_id', UuidFactory::uuid('acr.private_key.ada.id'))
            ->setField('recipient_fingerprint', '67BFFCB7B74AF4C85E81AB26508850525CD78BAA')
            ->setField('recipient_foreign_model', 'AccountRecoveryOrganizationKey')
            ->persist();

        return [$settings, $privateKey, $passwords];
    }

    public static function startToken(User $user): AuthenticationToken
    {
        /** @var AuthenticationToken $token */
        $token = AuthenticationTokenFactory::make()
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->userId($user->id)
            ->active()
            ->persist();

        return $token;
    }

    public static function startPolicy(): AccountRecoveryOrganizationPolicy
    {
        /** @var AccountRecoveryOrganizationPolicy $policy */
        $policy = AccountRecoveryOrganizationPolicyFactory::make()
            ->optin()
            ->with('AccountRecoveryOrganizationPublicKeys', AccountRecoveryOrganizationPublicKeyFactory::make()->patchData([
                'id' => UuidFactory::uuid('acr.org_public_key.id'),
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'),
                'deleted' => null,
            ]))
            ->persist();

        return $policy;
    }

    public static function startRequestApproved(User $user, AuthenticationToken $token): AccountRecoveryRequest
    {
        /** @var AccountRecoveryRequest $request */
        $request = AccountRecoveryRequestFactory::make()
            ->approved()
            ->withUser($user->id)
            ->setField('authentication_token_id', $token->id)
            ->with('AccountRecoveryResponses', AccountRecoveryResponseFactory::make()->patchData([
                'status' => AccountRecoveryResponse::STATUS_APPROVED,
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'),
                'deleted' => null,
            ]))
            ->persist();

        return $request;
    }

    public static function startRequestWrongUser(User $user, AuthenticationToken $token): AccountRecoveryRequest
    {
        /** @var AccountRecoveryRequest $request */
        $request = AccountRecoveryRequestFactory::make()
            ->approved()
            ->setField('user_id', UuidFactory::uuid())
            ->setField('authentication_token_id', $token->id)
            ->with('AccountRecoveryResponses', AccountRecoveryResponseFactory::make()->patchData([
                'status' => AccountRecoveryResponse::STATUS_APPROVED,
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'),
                'deleted' => null,
            ]))
            ->persist();

        return $request;
    }

    public static function startRequestWrongToken(User $user, AuthenticationToken $token): AccountRecoveryRequest
    {
        /** @var AccountRecoveryRequest $request */
        $request = AccountRecoveryRequestFactory::make()
            ->approved()
            ->setField('user_id', $user->id)
            ->setField('authentication_token_id', $token->token)
            ->with('AccountRecoveryResponses', AccountRecoveryResponseFactory::make()->patchData([
                'status' => AccountRecoveryResponse::STATUS_APPROVED,
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'),
                'deleted' => null,
            ]))
            ->persist();

        return $request;
    }
}
