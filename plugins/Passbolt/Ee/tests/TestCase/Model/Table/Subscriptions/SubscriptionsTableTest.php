<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.0.0
 */

namespace Passbolt\Ee\Test\TestCase\Model\Table\Subscriptions;

use App\Model\Entity\OrganizationSetting;
use App\Test\Factory\OrganizationSettingFactory;
use Cake\Http\Exception\UnauthorizedException;
use Cake\ORM\Exception\PersistenceFailedException;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Passbolt\Ee\Error\Exception\Subscriptions\SubscriptionRecordNotFoundException;
use Passbolt\Ee\Model\Entity\Subscription;
use Passbolt\Ee\Test\Factory\SubscriptionFactory;
use Passbolt\Ee\Test\Lib\DummySubscriptionTrait;

/**
 * Class SubscriptionsTableTest
 *
 * @package Passbolt\Ee\Test\TestCase\Model\Table
 * @covers \Passbolt\Ee\Model\Table\SubscriptionsTable
 */
class SubscriptionsTableTest extends TestCase
{
    use DummySubscriptionTrait;

    /**
     * @var SubscriptionsTable
     */
    public $Subscriptions;

    public function setUp()
    {
        parent::setUp();

        $this->setUpPathAndPublicSubscriptionKey();

        $this->Subscriptions = TableRegistry::getTableLocator()->get('Passbolt/Ee.Subscriptions');

        OrganizationSettingFactory::make(2)->persist();
    }

    public function tearDown()
    {
        unset($this->Subscriptions);
    }

    public function testInsertMultipleShouldFail()
    {
        $this->expectException(PersistenceFailedException::class);
        $this->persistValidSubscription();
        $this->persistValidSubscription();
    }

    public function testExists()
    {
        $this->assertFalse($this->Subscriptions->exists());
        $this->persistValidSubscription();
        $this->assertTrue($this->Subscriptions->exists());
    }

    public function testFindAmongMultipleOrganisationSettings()
    {
        $persistedSub = SubscriptionFactory::make()->persist();
        $retrievedSub = $this->Subscriptions->getOrFail();
        $this->assertInstanceOf(Subscription::class, $retrievedSub);
        $this->assertSame($persistedSub->id, $retrievedSub->id);
    }

    public function testSaveValidSubscriptionWithoutUserAuthenticated()
    {
        $this->expectException(UnauthorizedException::class);
        $value = $this->getValidSubscriptionKey();
        $entity = $this->Subscriptions->newEntity(compact('value'));
        $this->Subscriptions->saveOrFail($entity);
        $this->Subscriptions->getOrFail();
    }

    public function testSaveValidSubscriptionWithDummyUserAuthenticated()
    {
        $this->expectException(UnauthorizedException::class);

        $uac = $this->getDummyUserMock();

        $value = $this->getValidSubscriptionKey();
        $entity = $this->Subscriptions->newEntity(compact('value'));
        $this->Subscriptions->saveOrFail($entity, compact('uac'));
        $this->Subscriptions->getOrFail();
    }

    public function testSaveValidSubscriptionWithAdminAuthenticated()
    {
        $uac = $this->getDummyAdminUACMock();

        $value = $this->getValidSubscriptionKey();
        $entity = $this->Subscriptions->newEntity(compact('value'));
        $this->Subscriptions->saveOrFail($entity, compact('uac'));
        $subscription = $this->Subscriptions->getOrFail();
        $this->assertInstanceOf(OrganizationSetting::class, $subscription);
    }

    public function testGetValueOrFailOnEmptySubscriptionTable()
    {
        $this->expectException(SubscriptionRecordNotFoundException::class);
        $this->Subscriptions->getValueOrFail();
    }

    public function testGetValueOrFailOnNonExistentTable()
    {
        try {
            $this->Subscriptions->setTable('nonexistent_table');
            $this->Subscriptions->getValueOrFail();
        } catch (SubscriptionRecordNotFoundException $e) {
            $message = $e->getMessage();
            TableRegistry::getTableLocator()->clear();
        }
        $this->assertSame(SubscriptionRecordNotFoundException::MESSAGE, $message);
    }

    public function testGetValueOrFailOnPopulatedSubscriptionTable()
    {
        $this->persistValidSubscription();
        $this->assertSame(
            trim($this->getValidSubscriptionKey()),
            $this->Subscriptions->getValueOrFail()
        );
    }
}
