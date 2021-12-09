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
use App\Test\Factory\UserFactory;
use Cake\ORM\TableRegistry;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryIntegrationTestCase;

class SetupCompleteControllerTest extends AccountRecoveryIntegrationTestCase
{
    public $AuthenticationTokens;

    public function setUp(): void
    {
        $this->AuthenticationTokens = TableRegistry::getTableLocator()->get('AuthenticationTokens');
        parent::setUp();
    }

    /**
     * @group AN
     * @group setup
     * @group setupComplete
     */
    public function testSetupCompleteSuccess()
    {
        $user = UserFactory::make()->inactive()->persist();
        $token = $this->AuthenticationTokens->generate($user->id, AuthenticationToken::TYPE_REGISTER);
        $this->markTestIncomplete();

        $armoredKey = file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ruth_public.key');
        $data = [
            'authenticationtoken' => [
                'token' => $token->token,
            ],
            'gpgkey' => [
                'armored_key' => $armoredKey,
            ],
            'user' => [
                'locale' => 'fr_FR', // Putting on purpose an underscore, though convention is dashed.
            ],
        ];
        $this->postJson('/setup/complete/' . $user->id . '.json', $data);
        $this->assertSuccess();
    }
}
