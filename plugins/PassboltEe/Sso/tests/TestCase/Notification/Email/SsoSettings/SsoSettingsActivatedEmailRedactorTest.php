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
 * @since         4.0.0
 */

namespace Passbolt\Sso\Test\TestCase\Notification\Email\SsoSettings;

use App\Model\Entity\Role;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Utility\ExtendedUserAccessControl;
use App\Utility\UuidFactory;
use Cake\Event\Event;
use Cake\I18n\FrozenTime;
use Passbolt\Sso\Notification\Email\SsoSettings\SsoSettingsActivatedEmailRedactor;
use Passbolt\Sso\Service\SsoSettings\SsoSettingsActivateService;
use Passbolt\Sso\Test\Factory\SsoSettingsFactory;

/**
 * @covers \Passbolt\Sso\Notification\Email\SsoSettings\SsoSettingsActivatedEmailRedactor
 */
class SsoSettingsActivatedEmailRedactorTest extends AppTestCase
{
    /**
     * @var \Passbolt\Sso\Notification\Email\SsoSettings\SsoSettingsActivatedEmailRedactor
     */
    private $sut;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new SsoSettingsActivatedEmailRedactor();
        $this->loadPlugins(['Passbolt/Locale' => []]);
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->sut);

        parent::tearDown();
    }

    public function testSsoSettingsActivatedEmailRedactor_EmailIsSubscribedToEvent()
    {
        $this->assertTrue(
            in_array(
                SsoSettingsActivateService::AFTER_ACTIVATE_SSO_SETTINGS_EVENT,
                $this->sut->getSubscribedEvents()
            )
        );
    }

    public function testSsoSettingsActivatedEmailRedactor_ErrorMissingUac()
    {
        $event = new Event(SsoSettingsActivateService::AFTER_ACTIVATE_SSO_SETTINGS_EVENT);
        $event->setData([]);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('`uac` is missing from event data');

        $this->sut->onSubscribedEvent($event);
    }

    public function testSsoSettingsActivatedEmailRedactor_ErrorMissingSsoSetting()
    {
        $uac = new ExtendedUserAccessControl(
            Role::ADMIN,
            UuidFactory::uuid(),
            'foo@passbolt.test',
            '127.0.0.1',
            'Phpunit tests'
        );
        $event = new Event(SsoSettingsActivateService::AFTER_ACTIVATE_SSO_SETTINGS_EVENT);
        $event->setData(['uac' => $uac]);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('`ssoSetting` is missing from event data');

        $this->sut->onSubscribedEvent($event);
    }

    public function testSsoSettingsActivatedEmailRedactor_Success()
    {
        $ssoSetting = SsoSettingsFactory::make()->azure()->active()->persist();
        // Create users to test
        $operator = UserFactory::make()->admin()->persist();
        $admin1 = UserFactory::make(['created' => FrozenTime::now()->subDay(1)])->admin()->persist(); // created set for predictable result
        $admin2 = UserFactory::make(['created' => FrozenTime::now()])->admin()->persist();
        UserFactory::make()->user()->persist();
        $uac = new ExtendedUserAccessControl(
            Role::ADMIN,
            $operator->id,
            $operator->username,
            '127.0.0.1',
            'Phpunit tests'
        );
        // Prepare event
        $event = new Event(SsoSettingsActivateService::AFTER_ACTIVATE_SSO_SETTINGS_EVENT);
        $event->setData(['uac' => $uac, 'ssoSetting' => $ssoSetting]);

        $emailCollection = $this->sut->onSubscribedEvent($event);

        $this->assertCount(2, $emailCollection->getEmails());
        $email = $emailCollection->getEmails()[0];
        $this->assertEquals("{$operator->profile->first_name} activated the SSO setting", $email->getSubject());
        $this->assertEquals(SsoSettingsActivatedEmailRedactor::TEMPLATE, $email->getTemplate());
        $this->assertEquals($admin1->username, $email->getRecipient());
        $emailDataBody = $email->getData()['body'];
        $this->assertEquals($operator->username, $emailDataBody['operator']->username);
        $this->assertEquals($ssoSetting, $emailDataBody['ssoSetting']);
        $this->assertEquals($admin1->username, $emailDataBody['recipient']->username);
        $this->assertEquals($uac->getUserIp(), $emailDataBody['ip']);
        $this->assertEquals($uac->getUserAgent(), $emailDataBody['user_agent']);
    }
}
