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
 * @since         2.13.0
 */

namespace App\Test\TestCase\View\Helper;

use App\Service\Avatars\AvatarsConfigurationService;
use App\Test\Factory\AvatarFactory;
use App\Utility\UuidFactory;
use App\View\Helper\AvatarHelper;
use Cake\Core\Configure;
use Cake\Http\ServerRequest;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use RuntimeException;

/**
 * @covers \App\View\Helper\AvatarHelper
 */
class AvatarHelperTest extends TestCase
{
    /**
     * @var string
     */
    public $fullBaseUrl;

    public function setUp(): void
    {
        parent::setUp();
        $this->fullBaseUrl = Configure::readOrFail('App.fullBaseUrl');
        $this->loadRoutes();
        (new AvatarsConfigurationService())->loadConfiguration();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->fullBaseUrl);
    }

    public function testGetDefaultAvatarUrl()
    {
        TableRegistry::getTableLocator()->get('Avatars');

        $this->assertSame(
            $this->fullBaseUrl . '/img/avatar/user.png',
            AvatarHelper::getAvatarUrl()
        );

        $this->assertSame(
            $this->fullBaseUrl . '/img/avatar/user.png',
            AvatarHelper::getAvatarUrl(null, AvatarsConfigurationService::FORMAT_SMALL)
        );

        $this->assertSame(
            $this->fullBaseUrl . '/img/avatar/user_medium.png',
            AvatarHelper::getAvatarUrl(null, AvatarsConfigurationService::FORMAT_MEDIUM)
        );

        $this->expectException(RuntimeException::class);
        AvatarHelper::getAvatarUrl(null, 'large');
    }

    public function testGetExistingAvatarUrl()
    {
        $avatar = AvatarFactory::make(['id' => UuidFactory::uuid()])->getEntity();
        $expectedUrl = $this->fullBaseUrl . '/avatars/view/' . $avatar->get('id') . '/' . AvatarsConfigurationService::FORMAT_SMALL . AvatarHelper::IMAGE_EXTENSION;
//        // We are performing a unit test here. But the routes are loaded in the Middleware in CakePHP4
//        // Therefore an application needs to be build, which is here made using a call to a dummy url (an avatar one)
//        $this->get($expectedUrl);
//        $this->assertResponseOk();

        // We now test the AvatarHelper as such.
        $this->assertSame(
            $expectedUrl,
            AvatarHelper::getAvatarUrl([
                'id' => $avatar['id'],
                'data' => $avatar['data'],
            ])
        );
    }

    public function testGetValidImageFormats()
    {
        $expected = ['medium', 'small'];
        $this->assertSame($expected, AvatarHelper::getValidImageFormats(false));

        $expected = ['medium.jpg', 'small.jpg'];
        $this->assertSame($expected, AvatarHelper::getValidImageFormats());
    }

    public function testDefaultAvatarUrlIsNotBrokenWhenAppBaseIsSet()
    {
        Router::setRequest(new ServerRequest(['base' => '/subdir']));

        $result = AvatarHelper::getAvatarUrl();

        $this->assertSame("{$this->fullBaseUrl}/subdir/img/avatar/user.png", $result);
    }

    public function testUserAvatarUrlWhenAppBaseIsSet()
    {
        Router::setRequest(new ServerRequest(['base' => '/subdir']));
        $avatar = AvatarFactory::make(['id' => UuidFactory::uuid()])->getEntity();

        $result = AvatarHelper::getAvatarUrl(['id' => $avatar->id]);

        $this->assertSame(
            sprintf(
                '%s/subdir/avatars/view/%s/%s%s',
                $this->fullBaseUrl,
                $avatar->id,
                AvatarsConfigurationService::FORMAT_SMALL,
                AvatarHelper::IMAGE_EXTENSION
            ),
            $result
        );
    }
}
