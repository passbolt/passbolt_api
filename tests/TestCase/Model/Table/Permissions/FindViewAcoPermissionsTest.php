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
 * @since         2.0.0
 */

namespace App\Test\TestCase\Model\Table\Permissions;

use App\Model\Table\PermissionsTable;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Exception;

class FindViewAcoPermissionsTest extends AppTestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PermissionsTable
     */
    public $Permissions;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Permissions') ? [] : ['className' => PermissionsTable::class];
        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions', $config);
    }

    public function testContainUser()
    {
        $resource = ResourceFactory::make()
            ->withPermissionsFor([UserFactory::make()->persist()])
            ->persist();
        $options['contain']['user'] = true;
        $permissions = $this->Permissions->findViewAcoPermissions($resource->id, $options)->all();

        // retrieve a direct user permission
        $permission = Hash::extract($permissions->toArray(), '{n}[aro=User]')[0];
        $this->assertPermissionAttributes($permission);
        $this->assertUserAttributes($permission->user);
    }

    public function testContainUserProfile()
    {
        $resource = ResourceFactory::make()
            ->withPermissionsFor([UserFactory::make()->persist()])
            ->persist();
        $options['contain']['user.profile'] = true;
        $permissions = $this->Permissions->findViewAcoPermissions($resource->id, $options)->all();

        // retrieve a direct user permission
        $permission = Hash::extract($permissions->toArray(), '{n}[aro=User]')[0];
        $this->assertPermissionAttributes($permission);
        $this->assertProfileAttributes($permission->user->profile);
    }

    public function testContainUserProfileAvatar()
    {
        $resource = ResourceFactory::make()
            ->withPermissionsFor([UserFactory::make()->withAvatar()->persist()])
            ->persist();
        $options['contain']['user.profile'] = true;
        $permissions = $this->Permissions->findViewAcoPermissions($resource->id, $options)->all();

        // retrieve a direct user permission
        $permission = Hash::extract($permissions->toArray(), '{n}[aro=User]')[0];
        $this->assertPermissionAttributes($permission);
        $this->assertProfileAttributes($permission->user->profile);
        $this->assertObjectHasAttributes(['small', 'medium'], $permission->user->profile->avatar->url);
    }

    public function testErrorInvalidAcoForeignKeyParameter()
    {
        try {
            $this->Permissions->findViewAcoPermissions('not-valid');
        } catch (Exception $e) {
            return $this->assertTrue(true);
        }
        $this->fail('Expect an exception');
    }
}
