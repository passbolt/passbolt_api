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
 * @since         4.11.0
 */

namespace Passbolt\Metadata\Test\TestCase\Notification\Email\Redactor;

use App\Controller\Setup\SetupCompleteController;
use App\Notification\Email\Redactor\AdminUserSetupCompleteEmailRedactor;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCaseV5;
use App\Test\Lib\Model\EmailQueueTrait;
use Cake\Core\Configure;
use Cake\Event\Event;
use Passbolt\Metadata\Test\Factory\MetadataKeysSettingsFactory;

class AdminUserSetupCompleteEmailRedactorTest extends AppTestCaseV5
{
    use EmailQueueTrait;

    /**
     * @var AdminUserSetupCompleteEmailRedactor
     */
    private $sut;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        Configure::write('passbolt.plugins.log.enabled', true);
        $this->loadPlugins(['Passbolt/Locale' => []]);
        $this->sut = new AdminUserSetupCompleteEmailRedactor();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->sut);
        parent::tearDown();
    }

    public function testAdminUserSetupCompleteEmailRedactor_ZeroKnowledgeKeyShare()
    {
        UserFactory::make()->admin()->active()->persist();
        $user = UserFactory::make()->user()->active()->persist();

        $event = new Event(SetupCompleteController::COMPLETE_SUCCESS_EVENT_NAME);
        $event->setData(['user' => $user, 'data' => []]);
        $emailCollection = $this->sut->onSubscribedEvent($event);

        $email = $emailCollection->getEmails()[0];
        $body = $email->getData()['body'];
        $this->assertArrayHasKey('missingMetadataKey', $body);
        $this->assertTrue($body['missingMetadataKey']);
    }

    public function testAdminUserSetupCompleteEmailRedactor_ZeroKnowledgeKeyShareDisabled()
    {
        MetadataKeysSettingsFactory::make()->disableZeroTrustKeySharing()->persist();
        UserFactory::make()->admin()->active()->persist();
        $user = UserFactory::make()->user()->active()->persist();

        $event = new Event(SetupCompleteController::COMPLETE_SUCCESS_EVENT_NAME);
        $event->setData(['user' => $user, 'data' => []]);
        $emailCollection = $this->sut->onSubscribedEvent($event);

        $email = $emailCollection->getEmails()[0];
        $body = $email->getData()['body'];
        $this->assertArrayHasKey('missingMetadataKey', $body);
        $this->assertFalse($body['missingMetadataKey']);
    }
}
