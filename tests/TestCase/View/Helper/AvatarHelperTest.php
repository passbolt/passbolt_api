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
 * @since         2.13.0
 */

namespace App\Test\TestCase\View\Helper;

use App\Model\Entity\Avatar;
use App\View\Helper\AvatarHelper;
use Cake\Core\Configure;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\View\Helper\AvatarHelper
 */
class AvatarHelperTest extends TestCase
{
    const FULL_BASE_URL = 'http://mydomain.com';

    /**
     * @dataProvider provideAvatarsWithoutScheme
     * @dataProvider provideAvatarsWithScheme
     * @param Avatar $avatar An avatar instance
     * @param string $fullBaseUrl The current full base url
     * @param string $expectedUrl The expected URL for the avatar
     */
    public function testThatGetAvatarUrlReturnWellFormatedUrl(Avatar $avatar, string $fullBaseUrl, string $expectedUrl)
    {
        Configure::write('App.fullBaseUrl', $fullBaseUrl);

        $this->assertSame($expectedUrl, AvatarHelper::getAvatarUrl($avatar));
    }

    /**
     * Return local file paths
     * @return array
     */
    public function provideAvatarsWithoutScheme()
    {
        return [
            'empty avatar (default)' => [
                $this->createAvatarMock('img/avatar/user.png'),
                static::FULL_BASE_URL,
                static::FULL_BASE_URL . '/' . 'img/avatar/user.png',
            ],
            'avatar with relative path' => [
                $this->createAvatarMock('./img/avatar/uuid/uuid/uuid.png'),
                static::FULL_BASE_URL,
                static::FULL_BASE_URL . '/' . 'img/avatar/uuid/uuid/uuid.png',
            ],
            'avatar with absolute path' => [
                $this->createAvatarMock('/img/avatar/uuid/uuid/uuid.png'),
                static::FULL_BASE_URL,
                static::FULL_BASE_URL . '/' . 'img/avatar/uuid/uuid/uuid.png',
            ],
            'avatar with absolute path and appFullBaseUrl with trailing slash' => [
                $this->createAvatarMock('/img/avatar/uuid/uuid/uuid.png'),
                static::FULL_BASE_URL . '/',
                static::FULL_BASE_URL . '/' . 'img/avatar/uuid/uuid/uuid.png',
            ],
        ];
    }

    /**
     * Return url with a scheme
     * @return array
     */
    public function provideAvatarsWithScheme()
    {
        return [
            'avatar with http scheme' => [
                $this->createAvatarMock('https://storage.googleapis.com/gaufrette-remy-local/acme/Avatar/d9/38/ae/c8553fe26ba149eda9a3c36bec92f58c/c8553fe26ba149eda9a3c36bec92f58c.65a0ba70.png'),
                static::FULL_BASE_URL,
                'https://storage.googleapis.com/gaufrette-remy-local/acme/Avatar/d9/38/ae/c8553fe26ba149eda9a3c36bec92f58c/c8553fe26ba149eda9a3c36bec92f58c.65a0ba70.png',
            ],
            'avatar with ftp scheme' => [
                $this->createAvatarMock('ftp://storage.googleapis.com/gaufrette-remy-local/acme/Avatar/d9/38/ae/c8553fe26ba149eda9a3c36bec92f58c/c8553fe26ba149eda9a3c36bec92f58c.65a0ba70.png'),
                static::FULL_BASE_URL,
                'ftp://storage.googleapis.com/gaufrette-remy-local/acme/Avatar/d9/38/ae/c8553fe26ba149eda9a3c36bec92f58c/c8553fe26ba149eda9a3c36bec92f58c.65a0ba70.png',
            ],
        ];
    }

    /**
     * @param string $url URL to inject in the mock which must be returned when calling $avatar->url
     * @return MockObject
     */
    private function createAvatarMock(string $url)
    {
        $avatar = $this->createMock(Avatar::class);
        $avatar->method('__get')
            ->with('url')
            ->willReturn([
                'small' => $url,
            ]);

        return $avatar;
    }
}
