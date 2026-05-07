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
 * @since         3.9.0
 */

namespace Passbolt\Sso\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Passbolt\Sso\Test\Lib\SsoTestCase;

/**
 * @covers \Passbolt\Sso\Model\Table\SsoAuthenticationTokensTable
 */
class SsoSettingsTableTest extends SsoTestCase
{
    /**
     * @var \Passbolt\Sso\Model\Table\SsoSettingsTable
     */
    protected $SsoSettings;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->SsoSettings = TableRegistry::getTableLocator()->get('Passbolt/Sso.SsoSettings');
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->SsoSettings);
        parent::tearDown();
    }

    /**
     * New entity success
     *
     * @return void
     */
    public function testSsoSettingsTable_NewEntitySuccess(): void
    {
        $this->markTestIncomplete();
    }
}
