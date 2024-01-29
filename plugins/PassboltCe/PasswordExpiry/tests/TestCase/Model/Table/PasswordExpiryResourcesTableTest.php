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
 * @since         4.5.0
 */

namespace Passbolt\PasswordExpiry\Test\TestCase\Model\Table;

use App\Test\Lib\AppTestCase;
use Cake\I18n\FrozenDate;
use Cake\ORM\TableRegistry;

class PasswordExpiryResourcesTableTest extends AppTestCase
{
    /**
     * @var \App\Model\Table\ResourcesTable
     */
    public $Resources;

    public function setUp(): void
    {
        parent::setUp();
        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
    }

    public function tearDown(): void
    {
        unset($this->Resources);

        parent::tearDown();
    }

    public function dataProviderForSuccess(): array
    {
        return [
            [FrozenDate::tomorrow()->toAtomString()],
            [FrozenDate::tomorrow()->format('Y-m-d')],
            [null],
        ];
    }

    /**
     * @dataProvider dataProviderForSuccess
     */
    public function testPasswordExpiryResourcesTable_Expired_Valid(?string $expiryDate)
    {
        $resource = $this->Resources->newEntity([
            'expired' => $expiryDate,
        ]);
        $this->assertSame([], $resource->getError('expired'));
    }

    public function testPasswordExpiryResourcesTable_Expired_Invalid()
    {
        $resource = $this->Resources->newEntity([
            'expired' => 'foo',
        ]);
        $this->assertSame([
            'expired' => 'The date could not be parsed.',
        ], $resource->getError('expired'));
    }
}
