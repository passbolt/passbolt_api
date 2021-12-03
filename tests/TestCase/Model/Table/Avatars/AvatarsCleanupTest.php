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

namespace App\Test\TestCase\Model\Table\Avatars;

use App\Model\Table\AvatarsTable;
use App\Test\Factory\AvatarFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Model\AvatarsModelTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class AvatarsCleanupTest extends TestCase
{
    use AvatarsModelTrait;

    /**
     * Test subject
     *
     * @var \App\Model\Table\AvatarsTable
     */
    public $Avatars;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Avatars') ? [] : ['className' => AvatarsTable::class];
        $this->Avatars = TableRegistry::getTableLocator()->get('Avatars', $config);
        $this->loadRoutes();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Avatars);

        parent::tearDown();
    }

    public function hardDelete(): array
    {
        return [[false], [true]];
    }

    /**
     * @dataProvider hardDelete
     */
    public function testAvatarsCleanupDeletedUsers(bool $hardDelete)
    {
        // Create avatar with non deleted user
        UserFactory::make(1)->with('Profiles.Avatars')->persist();
        // Create avatar with deleted user - Soft
        UserFactory::make(2)->with('Profiles.Avatars')->deleted()->persist();
        // Create avatar with no user - Hard
        AvatarFactory::make(3)->withProfile()->persist();

        if ($hardDelete) {
            $output = $this->Avatars->cleanupHardDeletedUsers();
            $expectedOutput = 3;
        } else {
            $output = $this->Avatars->cleanupSoftDeletedUsers();
            $expectedOutput = 2;
        }
        $this->assertSame($expectedOutput, $output);
        $this->assertSame(6 - $expectedOutput, $this->Avatars->find()->count());
    }

    public function testAvatarsCleanupDeletedFavorites()
    {
        // Create avatar with profile
        AvatarFactory::make(1)->withProfile()->persist();
        // Create avatar with no profile
        AvatarFactory::make(2)->patchData(['profile_id' => UuidFactory::uuid('foo')])->persist();

        $output = $this->Avatars->cleanupHardDeletedProfiles();
        $this->assertSame(2, $output);
        $this->assertSame(1, $this->Avatars->find()->count());
    }
}
