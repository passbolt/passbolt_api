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

namespace App\Test\TestCase\Model\Table\Resources;

use App\Model\Table\ResourcesTable;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\FormatValidationTrait;
use Cake\ORM\TableRegistry;
use Migrations\Migrations;

class SoftDeleteAllTest extends AppTestCase
{
    use FormatValidationTrait;

    public $Resources;

    public $fixtures = [
        'app.Base/Favorites',
        'app.Base/Permissions',
        'app.Base/Resources',
        'app.Base/Secrets',
    ];

    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Resources') ? [] : ['className' => ResourcesTable::class];
        $this->Resources = TableRegistry::getTableLocator()->get('Resources', $config);
    }

    public function tearDown()
    {
        unset($this->Resources);

        parent::tearDown();
    }

    /**
     * @see ResourcesTable::softDeleteAll()
     */
    public function testSoftDeleteAllSuccess()
    {
        // Fetch the resources non deleted and with populated fields
        $resourcesId = $this->Resources
            ->find('list', ['valueField' => 'id'])
            ->where([
                'username IS NOT NULL',
                'uri IS NOT NULL',
                'description IS NOT NULL',
                'deleted IS FALSE',
            ])->toArray();

        // We'll make sure that at least two Resources are found in the fixtures
        $this->assertTrue(count($resourcesId) > 2);

        // The first will remain unchanged.
        $id1 = array_pop($resourcesId);

        // The others get soft deleted
        $this->Resources->softDeleteAll($resourcesId);

        // Fetch soft deleted resources
        $deletedResources = $this->Resources
            ->find('list', ['valueField' => 'id'])
            ->where([
                'username IS NULL',
                'uri IS NULL',
                'description IS NULL',
                'deleted IS TRUE',
            ])->toArray();

        $this->assertSame($resourcesId, $deletedResources);

        $unchangedResource = $this->Resources->get($id1);
        $this->assertTrue(strlen($unchangedResource->username) > 0);
        $this->assertTrue(strlen($unchangedResource->uri) > 0);
        $this->assertTrue(strlen($unchangedResource->description) > 0);
        $this->assertFalse($unchangedResource->deleted);
    }

    /**
     * Perform the tests with and without cascade
     *
     * @return array
     * @see ResourcesTable::softDeleteAll()
     */
    public function dataForTestSoftDeleteAllSuccessWithAssociation()
    {
        return [[true], [false]];
    }

    /**
     * @param bool $cascade
     * @dataProvider dataForTestSoftDeleteAllSuccessWithAssociation
     */
    public function testSoftDeleteAllSuccessWithAssociation(bool $cascade)
    {
        // Fetch the non deleted resources with populated fields
        $resourcesId = $this->Resources
            ->find('list', ['valueField' => 'id'])
            ->where([
                'username IS NOT NULL',
                'uri IS NOT NULL',
                'description IS NOT NULL',
                'deleted IS FALSE',
            ])->toArray();

        $associations = ['Favorites', 'Secrets', 'Permissions'];

        // Count associated entities
        $count = [];
        foreach ($associations as $association) {
            $count[$association] = $this->Resources->{$association}->find()->count();
            $this->assertTrue($count[$association] > 0, "No $association were found");
        }

        // Act
        $this->Resources->softDeleteAll($resourcesId, $cascade);

        // Check that the associated entities were deleted if cascade is true
        foreach ($associations as $association) {
            $count[$association] = $this->Resources->{$association}->find()->count();
            $expect = $cascade ? 0 : $count[$association];
            $this->assertSame($expect, $count[$association]);
        }
    }

    /**
     * @see \V300DeleteMetadataOfSoftDeletedResources::up()
     */
    public function testSoftDeleteCleanupWithMigration()
    {
        $this->markTestSkipped('Migrations cannot be run in the test environment for the moment');

        // Fetch the non deleted resources with populated fields
        $resourcesId = $this->Resources
            ->find('list', ['valueField' => 'id'])
            ->where([
                'username IS NOT NULL',
                'uri IS NOT NULL',
                'description IS NOT NULL',
                'deleted IS FALSE',
            ]);

        $associations = ['Favorites', 'Secrets', 'Permissions'];

        // Count associated entities
        $count = [];
        foreach ($associations as $association) {
            $count[$association] = $this->Resources->{$association}->find()->count();
            $this->assertTrue($count[$association] > 0, "No $association were found");
        }

        // Soft delete the resources found
        $this->Resources->updateAll(['deleted' => true], ['id IN' => $resourcesId]);

        // Mark as non migrated
        $this->Resources->getConnection()->execute('DELETE FROM phinxlog WHERE version = 20201221093528');

        (new Migrations())->migrate();

        // Fetch soft deleted resources
        $deletedResources = $this->Resources
            ->find()
            ->where([
                'username IS NULL',
                'uri IS NULL',
                'description IS NULL',
                'deleted IS TRUE',
            ]);

        $resources = $this->Resources->find();
        $this->assertSame($resources->count(), $deletedResources->count());
    }
}
