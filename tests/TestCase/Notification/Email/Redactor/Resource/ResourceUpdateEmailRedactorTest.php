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
 * @since         5.3.3
 */

namespace App\Test\TestCase\Notification\Email\Redactor\Resource;

use App\Model\Entity\Permission;
use App\Notification\Email\Redactor\Resource\ResourceUpdateEmailRedactor;
use App\Service\Resources\ResourcesUpdateService;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\SecretFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use Cake\Event\Event;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;
use Passbolt\Metadata\Test\Factory\MetadataTypesSettingsFactory;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

/**
 * @covers \App\Notification\Email\Redactor\Resource\ResourceUpdateEmailRedactor
 */
class ResourceUpdateEmailRedactorTest extends AppTestCase
{
    use EmailQueueTrait;
    use EmailNotificationSettingsTestTrait;

    private ?ResourceUpdateEmailRedactor $sut;

    public function setUp(): void
    {
        parent::setUp();
        $this->sut = new ResourceUpdateEmailRedactor();
        $this->loadPlugins(['Passbolt/Locale' => []]);
        RoleFactory::make()->guest()->persist();
        $this->loadNotificationSettings();
    }

    public function tearDown(): void
    {
        unset($this->sut);
        $this->unloadNotificationSettings();
        parent::tearDown();
    }

    public function testResourceUpdateEmailRedactor_V5_WithMultipleRecipients(): void
    {
        // metadata related setup
        MetadataTypesSettingsFactory::make()->v5()->persist();
        // users
        $owner = UserFactory::make()->admin()->active()->persist();
        $recepient1 = UserFactory::make()->user()->active()->persist();
        $recepient2 = UserFactory::make()->admin()->active()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make(['created_by' => $owner->id, 'modified_by' => $owner->id])
            ->v5Fields(true)
            ->with('ResourceTypes', ResourceTypeFactory::make()->v5Default())
            ->withPermissionsFor([$owner], Permission::OWNER)
            ->withPermissionsFor([$recepient1], Permission::READ)
            ->withPermissionsFor([$recepient2], Permission::UPDATE)
            ->withSecretsFor([$owner, $recepient1, $recepient2])
            ->persist();
        $uac = $this->makeUac($owner);
        $secrets = SecretFactory::find()->all()->toArray();

        $event = new Event(ResourcesUpdateService::UPDATE_SUCCESS_EVENT_NAME);
        $event->setData([
            'resource' => $resource,
            'accessControl' => $uac,
            'secrets' => $secrets,
            'isV5' => true,
        ]);
        $emailCollection = $this->sut->onSubscribedEvent($event);

        $this->assertCount(3, $emailCollection->getEmails());
        foreach ($emailCollection->getEmails() as $email) {
            $recipient = $email->getData()['body']['recipient'];
            if ($recipient->id === $owner->id) {
                $this->assertSame('You edited a resource', $email->getSubject());
            } else {
                $this->assertSame(sprintf('%s edited a resource', $owner->profile->first_name), $email->getSubject());
            }

            $this->assertSame(ResourceUpdateEmailRedactor::TEMPLATE_V5, $email->getTemplate());
        }
    }
}
