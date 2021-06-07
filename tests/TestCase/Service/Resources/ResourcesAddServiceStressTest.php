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
 * @since         3.3.0
 */

namespace App\Test\TestCase\Service\Resources;

use App\Model\Entity\Resource;
use App\Model\Entity\Secret;
use App\Service\Resources\ResourcesAddService;
use App\Test\Factory\ResourceTypeFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Model\ResourcesModelTrait;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\SkipTablesTruncation;
use CakephpTestSuiteLight\Sniffer\SnifferRegistry;

/**
 * This test aims at reproducing the load on the DB when performing parallel
 * import of resources.
 *
 * @covers \App\Service\Resources\ResourcesAddService
 * @see \App\Controller\Resources\ResourcesAddController
 */
class ResourcesAddServiceStressTest extends TestCase
{
    use ResourcesModelTrait;
    use SkipTablesTruncation;

    /**
     * @var User The user created once by testTruncateDirtyTables.
     */
    private static $user;

    /**
     * @var int The number of imports to perform. 300 or plus is a good value. Always set back to 0 when committing!!!.
     */
    private static $NIterations = 0;

    public static function setUpBeforeClass(): void
    {
        self::$user = TableRegistry::getTableLocator()->get('Users')->find()->contain('Roles')->first();
    }

    public function setUp(): void
    {
        $this->skipIf(self::$NIterations < 1);
        parent::setUp();
    }

    /**
     * Run this test once to clean the DB and create the required default resource type.
     * Deadlocks are more likely to happen in a DB of reduced size.
     */
    public function testTruncateDirtyTables()
    {
        SnifferRegistry::get('test')->truncateDirtyTables();
        $this->assertTrue(true);
        ResourceTypeFactory::make()->default()->persist();
        UserFactory::make()->user()->persist();
    }

    public function dataForStressTest(): array
    {
        $iter = [];
        foreach (range(1, self::$NIterations) as $i) {
            $iter[] = [$i];
        }

        return $iter;
    }

    /**
     * Run this test in 4 or more different consoles to
     * parallelize the creation of resources.
     * Check the logs.
     *
     * @dataProvider dataForStressTest
     */
    public function testResourceAddServiceSuccessStressTest(int $iter)
    {
        $data = $this->getDummyResourcesPostData();
        $service = new ResourcesAddService();

        $resource = $service->add(self::$user->id, $data);

        $this->assertInstanceOf(Resource::class, $resource);
        $this->assertInstanceOf(Secret::class, $resource->secrets[0]);
        $this->assertFalse($resource->hasErrors());
        $this->assertFalse($resource->secrets[0]->hasErrors());
    }
}
