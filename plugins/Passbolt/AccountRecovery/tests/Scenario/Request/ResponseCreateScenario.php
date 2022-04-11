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
namespace Passbolt\AccountRecovery\Test\Scenario\Request;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKeyPassword;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPolicyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPublicKeyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryPrivateKeyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryPrivateKeyPasswordFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryRequestFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryUserSettingFactory;

/**
 * MfaDuoScenario
 */
class ResponseCreateScenario implements FixtureScenarioInterface
{
    /**
     * @param array $args
     * @return array
     */
    public function load(...$args): array
    {
        /** @var \App\Utility\UserAccessControl|null $user */
        $status = $args[0] ?? AccountRecoveryRequest::ACCOUNT_RECOVERY_REQUEST_PENDING;

        $user = UserFactory::make()
            ->user()
            ->with('Gpgkeys', GpgkeyFactory::make()->adaPublicKey())
            ->withAvatar()
            ->persist();

        $policy = AccountRecoveryOrganizationPolicyFactory::make()
            ->optin()
            ->with(
                'AccountRecoveryOrganizationPublicKeys',
                AccountRecoveryOrganizationPublicKeyFactory::make()->rsa4096Key()
            )
            ->persist();

        AccountRecoveryUserSettingFactory::make()
            ->setField('status', 'approved')
            ->setField('user_id', $user->id)
            ->persist();

        AccountRecoveryPrivateKeyFactory::make()
            ->with('AccountRecoveryPrivateKeyPasswords', AccountRecoveryPrivateKeyPasswordFactory::make()
                ->setField('recipient_fingerprint', '67BFFCB7B74AF4C85E81AB26508850525CD78BAA')
                ->setField('recipient_foreign_model', AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY))
            ->setField('user_id', $user->id)
            ->persist();

        $authenticationToken = AuthenticationTokenFactory::make()
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->setField('user_id', $user->id)
            ->active()
            ->persist();

        $request = AccountRecoveryRequestFactory::make()
            ->rsa4096Key_2()
            ->setField('authentication_token_id', $authenticationToken->id)
            ->setField('status', $status)
            ->setField('user_id', $user->id)
            ->persist();

        return [$request, $policy, $user, $authenticationToken];
    }
}
