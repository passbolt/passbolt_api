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
 * @since         5.3.0
 */
namespace App\Test\TestCase\Service\Healthcheck\Application;

use App\Service\Healthcheck\Application\EmailNotificationEnabledApplicationHealthcheck;
use App\Test\Lib\AppTestCase;
use Cake\Core\Configure;
use Passbolt\EmailNotificationSettings\Test\Factory\EmailNotificationSettingFactory;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;

/**
 * @covers \App\Service\Healthcheck\Application\EmailNotificationEnabledApplicationHealthcheck
 */
class EmailNotificationEnabledApplicationHealthcheckTest extends AppTestCase
{
    use EmailNotificationSettingsTestTrait;

    private ?EmailNotificationEnabledApplicationHealthcheck $healthcheck = null;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->loadNotificationSettings();
        $this->healthcheck = new EmailNotificationEnabledApplicationHealthcheck();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        $this->unloadNotificationSettings();
        unset($this->healthcheck);

        parent::tearDown();
    }

    public function testEmailNotificationEnabledApplicationHealthcheck_Success_File(): void
    {
        Configure::write('passbolt.email.send.password.create', true);
        Configure::write('passbolt.email.send.comment.add', true);
        Configure::write('passbolt.email.send.user.create', true);
        Configure::write('passbolt.email.send.folder.create', true);

        $service = $this->healthcheck->check();

        $this->assertTrue($service->isPassed());
    }

    public function testEmailNotificationEnabledApplicationHealthcheck_Success_Database(): void
    {
        $defaults = static::getDefaultEmailNotificationConfig();
        $defaults['send']['password']['create'] = true;
        EmailNotificationSettingFactory::make()->setField('value', json_encode($defaults))->persist();
        $service = $this->healthcheck->check();
        $this->assertTrue($service->isPassed());
    }

    public function testEmailNotificationEnabledApplicationHealthcheck_Fail_WithDefault(): void
    {
        $service = $this->healthcheck->check();
        $this->assertFalse($service->isPassed());
    }
}
