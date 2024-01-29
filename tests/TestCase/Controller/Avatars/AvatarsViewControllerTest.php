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
 * @since         3.0.0
 */

namespace App\Test\TestCase\Controller\Avatars;

use App\Service\Avatars\AvatarsCacheService;
use App\Service\Avatars\AvatarsConfigurationService;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\AvatarsIntegrationTestTrait;
use App\Utility\UuidFactory;
use App\View\Helper\AvatarHelper;

/**
 * App\Controller\AvatarsController Test Case
 *
 * @uses \App\Controller\AvatarsController
 */
class AvatarsViewControllerTest extends AppIntegrationTestCase
{
    use AvatarsIntegrationTestTrait;

    public AvatarsCacheService $avatarsCacheService;

    public function setUp(): void
    {
        parent::setUp();
        $this->avatarsCacheService = new AvatarsCacheService($this->filesystemAdapter);
    }

    public function tearDown(): void
    {
        unset($this->avatarsCacheService);
        parent::tearDown();
    }

    public function validFormatDataProvider(): array
    {
        return [
            [AvatarsConfigurationService::FORMAT_SMALL],
            [AvatarsConfigurationService::FORMAT_MEDIUM],
        ];
    }

    /**
     * Test view method on non existent Avatar
     *
     * @dataProvider validFormatDataProvider
     * @param string $format
     * @return void
     * @throws \PHPUnit\Exception
     */
    public function testAvatarsViewController_ViewNonExistentAvatar(string $format)
    {
        $this->get('avatars/view/' . UuidFactory::Uuid() . '/' . $format . AvatarHelper::IMAGE_EXTENSION);
        $defaultAvatarFileName = $this->avatarsCacheService->getFallBackFileName();
        $this->assertResponseEquals(file_get_contents($defaultAvatarFileName));
        $this->assertContentType('jpg');
    }

    /**
     * Test view method on non existent Avatar
     *
     * @dataProvider validFormatDataProvider
     * @param string $format
     * @return void
     * @throws \PHPUnit\Exception
     */
    public function testViewAvatarsViewController_ViewExistentAvatar(string $format)
    {
        $avatar = $this->createAvatar();

        $expectedFileContent = $this->avatarsCacheService
            ->readSteamFromId($avatar->id, $format)
            ->getContents();

        $this->get('avatars/view/' . $avatar->id . '/' . $format . AvatarHelper::IMAGE_EXTENSION);
        $this->assertResponseEquals($expectedFileContent);
    }

    /**
     * Test view method on existent Avatar, which local storage has been deleted.
     *
     * @dataProvider validFormatDataProvider
     * @param string $format
     * @return void
     * @throws \PHPUnit\Exception
     */
    public function testAvatarsViewController_ViewExistentAvatarWithDeletedFile(string $format)
    {
        $avatar = $this->createAvatar();

        $expectedFileContent = $this->avatarsCacheService
            ->readSteamFromId($avatar->id, $format)
            ->getContents();

        // Delete the file previously saved on disk
        $this->filesystemAdapter->deleteDirectory('');

        $this->get('avatars/view/' . $avatar->id . '/' . $format . AvatarHelper::IMAGE_EXTENSION);
        $this->assertResponseEquals($expectedFileContent);
    }

    /**
     * Test view on a non valid format.
     *
     * @dataProvider validFormatDataProvider
     */
    public function testAvatarsViewController_ViewOnWrongExtension(string $format)
    {
        $avatar = $this->createAvatar();
        // Store avatar in cache
        $this->avatarsCacheService->readSteamFromId($avatar->id, $format)->getContents();
        $expectedFileContent = file_get_contents($this->avatarsCacheService->getFallBackFileName());

        $this->get('avatars/view/' . $avatar->id . '/' . $format . '.wrong_extension');
        $this->assertResponseEquals($expectedFileContent);
    }
}
