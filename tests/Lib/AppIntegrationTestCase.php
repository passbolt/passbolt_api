<?php
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
 * @since         2.0.0
 */
namespace App\Test\Lib;

use App\Model\Entity\Role;
use App\Test\Lib\Model\AvatarsModelTrait;
use App\Test\Lib\Model\GpgkeysModelTrait;
use App\Test\Lib\Model\PermissionsModelTrait;
use App\Test\Lib\Model\ProfilesModelTrait;
use App\Test\Lib\Model\ResourcesModelTrait;
use App\Test\Lib\Model\RolesModelTrait;
use App\Test\Lib\Model\SecretsModelTrait;
use App\Test\Lib\Model\UsersModelTrait;
use App\Test\Lib\Utility\ArrayTrait;
use App\Test\Lib\Utility\EntityTrait;
use App\Test\Lib\Utility\ErrorTrait;
use App\Test\Lib\Utility\JsonRequestTrait;
use App\Test\Lib\Utility\ObjectTrait;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

abstract class AppIntegrationTestCase extends TestCase
{
    use ArrayTrait;
    use AvatarsModelTrait;
    use EntityTrait;
    use ErrorTrait;
    use GpgkeysModelTrait;
    use IntegrationTestTrait;
    use JsonRequestTrait;
    use ObjectTrait;
    use PermissionsModelTrait;
    use ProfilesModelTrait;
    use ResourcesModelTrait;
    use RolesModelTrait;
    use SecretsModelTrait;
    use UsersModelTrait;

    /**
     * The response for the most recent json request.
     *
     * @var Object|array
     */
    protected $_responseJson;

    /**
     * The response header for the most recent json request.
     *
     * @var Object
     */
    protected $_responseJsonHeader;

    /**
     * The response body for the most recent json request.
     *
     * @var Object
     */
    protected $_responseJsonBody;

    /**
     * Setup.
     */
    public function setUp()
    {
        parent::setUp();
        $this->enableCsrfToken();
        Configure::write('passbolt.plugins.log.enabled', false);
    }

    /**
     * Authenticate as a user.
     *
     * @param string $userFirstName The user first name.
     * @return void
     */
    public function authenticateAs($userFirstName)
    {
        $data = [
            'id' => UuidFactory::uuid('user.id.' . $userFirstName),
            'username' => $userFirstName . '@passbolt.com',
            'profile' => [
                'first_name' => $userFirstName,
                'last_name' => 'testing',
            ],
            'role' => [
                'name' => Role::USER
            ]
        ];
        if ($userFirstName === 'admin') {
            $data['role']['name'] = Role::ADMIN;
        }
        $this->session(['Auth' => ['User' => $data]]);
    }

    /**
     * Calling this method will remove the CSRF token from the request.
     *
     * @return void
     */
    public function disableCsrfToken()
    {
        $this->_csrfToken = false;
    }
}
