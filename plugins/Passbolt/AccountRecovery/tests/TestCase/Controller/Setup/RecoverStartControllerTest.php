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
 * @since         3.5.0
 */
namespace Passbolt\AccountRecovery\Test\TestCase\Controller\Setup;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryUserSettingFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryIntegrationTestCase;

class RecoverStartControllerTest extends AccountRecoveryIntegrationTestCase
{
    /**
     * @group AN
     * @group setup
     * @group setupStart
     */
    public function testRecoverStartSuccess()
    {
        $status = 'Foo';
        $setting = AccountRecoveryUserSettingFactory::make()
            ->withUser()
            ->setField('status', $status)
            ->persist();
        $user = $setting->user;
        $token = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->userId($user->id)
            ->persist();

        $url = "/setup/recover/start/{$user->id}/{$token->token}.json";
        $this->getJson($url);
        $this->assertResponseOk();
        $this->assertObjectHasAttribute('user', $this->_responseJsonBody);
        $this->assertSame(compact('status'), (array)$this->_responseJsonBody->account_recovery_user_setting);
    }
}
