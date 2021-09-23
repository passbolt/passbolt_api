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

namespace App\Test\TestCase\Model\Table\AuthenticationTokens;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Lib\AppTestCase;
use Cake\Datasource\ModelAwareTrait;
use Cake\I18n\Date;
use CakephpTestSuiteLight\SkipTablesTruncation;

/**
 * @property \App\Model\Table\AuthenticationTokensTable $AuthenticationTokens
 */
class IsExpiredTest extends AppTestCase
{
    use ModelAwareTrait;
    use SkipTablesTruncation;

    public function setUp(): void
    {
        $this->loadModel('AuthenticationTokens');
    }

    public function expiryData(): array
    {
        return [
            [null, false],
            ['', false],
            ['1 hour', true],
            ['1 week', false],
        ];
    }

    /**
     * @dataProvider expiryData
     */
    public function testAuthenticationTokensIsExpired($expiry, bool $isExpired)
    {
        $token = AuthenticationTokenFactory::make()
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->created(Date::yesterday())
            ->getEntity();

        $result = $this->AuthenticationTokens->isExpired($token, $expiry);
        $this->assertSame($isExpired, $result);
    }
}
