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
 * @since         2.0.0
 */

namespace Passbolt\AccountSettings\Test\TestCase\Model\Table;

use BadMethodCallException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\TestSuite\TestCase;
use Passbolt\AccountSettings\Model\Entity\AccountSetting;
use Passbolt\AccountSettings\Test\Factory\AccountSettingFactory;

/**
 * Passbolt\AccountSettings\Model\Table\AccountSettingsTable Test Case
 *
 * @property \Passbolt\AccountSettings\Model\Table\AccountSettingsTable $AccountSettings
 */
class AccountSettingsTableTest extends TestCase
{
    use LocatorAwareTrait;

    /**
     * @var \Passbolt\AccountSettings\Model\Table\AccountSettingsTable
     */
    protected $AccountSettings;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->AccountSettings = $this->fetchTable('AccountSettings');
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->AccountSettings);

        parent::tearDown();
    }

    public function testFindByProperty(): void
    {
        $property = 'Foo';
        $value = 'Bar';

        // Expect exception if no property is passed
        $this->expectException(BadMethodCallException::class);
        $this->AccountSettings->find('byProperty');

        // Return null if the property does not exist
        /** @var mixed|null $result */
        $result = $this->AccountSettings->find('byProperty', compact('property'));
        $this->assertSame(null, $result);

        // Now all good with an existing acount setting
        AccountSettingFactory::make()->setPropertyValue($property, $value)->persist();
        /** @var mixed|null $result */
        $result = $this->AccountSettings->find('byProperty', compact('property'));
        $this->assertInstanceOf(AccountSetting::class, $result);
        $this->assertSame($property, $result->get('property'));
        $this->assertSame($value, $result->get('value'));
    }
}
