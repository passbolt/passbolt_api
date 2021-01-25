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

use App\Model\Table\AvatarsTable;
use App\Test\Lib\Model\AvatarsModelTrait;
use App\View\Helper\AvatarHelper;
use Cake\Core\Configure;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\View\Helper\AvatarHelper
 */
class AvatarHelperTest extends TestCase
{
    use AvatarsModelTrait;

    public const FULL_BASE_URL = 'http://mydomain.com';

    public function setUp()
    {
        Configure::write('App.fullBaseUrl', self::FULL_BASE_URL);
    }

    public function testGetDefaultAvatarUrl()
    {
        $this->assertSame(
            self::FULL_BASE_URL . '/img/avatar/user.png',
            AvatarHelper::getAvatarUrl()
        );

        $this->assertSame(
            self::FULL_BASE_URL . '/img/avatar/user.png',
            AvatarHelper::getAvatarUrl(null, AvatarsTable::FORMAT_SMALL)
        );

        $this->assertSame(
            self::FULL_BASE_URL . '/img/avatar/user_medium.png',
            AvatarHelper::getAvatarUrl(null, AvatarsTable::FORMAT_MEDIUM)
        );

        $this->expectException(\RuntimeException::class);
        AvatarHelper::getAvatarUrl(null, 'large');
    }

    public function testGetExistingAvatarUrl()
    {
        $avatar = $this->createAvatar();

        $this->assertSame(
            self::FULL_BASE_URL . '/avatars/view/' . $avatar->get('id') . '/' . AvatarsTable::FORMAT_SMALL,
            AvatarHelper::getAvatarUrl($avatar)
        );
    }
}
