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
 * @since         4.3.0
 */

namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use Cake\I18n\DateTime;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\TestSuite\TestCase;

class AuthenticationTokenIsExpiredTest extends TestCase
{
    use LocatorAwareTrait;

    /**
     * @var \App\Model\Table\AuthenticationTokensTable
     */
    protected $AuthenticationTokens;

    public function setUp(): void
    {
        parent::setUp();
        $this->AuthenticationTokens = $this->fetchTable('AuthenticationTokens');
    }

    public function expiryData(): array
    {
        return [
            [AuthenticationToken::TYPE_REFRESH_TOKEN, false], // month
            [AuthenticationToken::TYPE_RECOVER, false], // days
            [AuthenticationToken::TYPE_VERIFY_TOKEN, true], // hour
            [AuthenticationToken::TYPE_LOGIN, true], // minutes
        ];
    }

    /**
     * @dataProvider expiryData
     */
    public function testAuthenticationTokens_Created_Yesterday($type, bool $isExpired)
    {
        /** @var \App\Model\Entity\AuthenticationToken $token */
        $token = AuthenticationTokenFactory::make()
            ->type($type)
            ->created(DateTime::yesterday())
            ->getEntity();

        $result = $this->AuthenticationTokens->isExpired($token);
        $this->assertSame($isExpired, $result);
    }
}
