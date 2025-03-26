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

namespace Passbolt\Subscription\Test;

use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\ORM\TableRegistry;
use Passbolt\Subscription\Service\Subscriptions\SubscriptionKeyGetService;
use RuntimeException;

/**
 * Class DummySubscriptionTrait
 *
 * @property \Passbolt\Subscription\Model\Table\SubscriptionsTable $Subscriptions
 */
trait DummySubscriptionTrait
{
    protected $baseTestPath = PLUGINS . 'PassboltEe' . DS . 'Subscription' . DS . 'tests' . DS;

    protected function setUpPathAndPublicSubscriptionKey()
    {
        $subscriptionDevPublicKey = $this->baseTestPath . 'Fixture' . DS . 'gpg' . DS . 'subscription_dev_public.key';
        if (!file_exists($subscriptionDevPublicKey)) {
            throw new RuntimeException('Cannot find dummy subscription key file ' . $subscriptionDevPublicKey);
        }

        Configure::write('passbolt.plugins.subscription.subscriptionKey.public', $subscriptionDevPublicKey);
    }

    /**
     * Get a dummy license file.
     * See tests/data/license
     *
     * @param string $scenario
     * @return string
     */
    protected function getDummySubscriptionKey(string $scenario): string
    {
        return file_get_contents($this->getDummySubscriptionFileName($scenario));
    }

    protected function getDummySubscriptionFileName(string $scenario): string
    {
        $file = PLUGINS . DS . 'PassboltEe' . DS . 'Subscription' . DS . 'tests' . DS . 'Fixture' . DS . 'subscription' . DS . $scenario;
        if (!file_exists($file)) {
            throw new RuntimeException('Cannot find dummy file ' . $file);
        }

        return $file;
    }

    protected function getValidSubscriptionFileName(): string
    {
        return $this->getDummySubscriptionFileName('subscription_dev');
    }

    protected function getExpiredSubscriptionFileName(): string
    {
        return $this->getDummySubscriptionFileName('subscription_expired');
    }

    protected function getValidSubscriptionKey(): string
    {
        return $this->getDummySubscriptionKey('subscription_dev');
    }

    protected function getExpiredSubscriptionKey(): string
    {
        return $this->getDummySubscriptionKey('subscription_expired');
    }

    protected function getValidSubscription(): array
    {
        return [
            'customer_id' => 'test',
            'subscription_id' => 'test',
            'users' => 2,
            'email' => 'test@passbolt.com',
            'expiry' => '2025-06-01',
            'created' => '2018-03-01',
        ];
    }

    protected function getExpiredSubscription(): array
    {
        return [
            'customer_id' => 'test',
            'subscription_id' => 'test',
            'users' => 2,
            'email' => 'test@passbolt.com',
            'expiry' => '2020-06-01',
            'created' => '2018-03-01',
        ];
    }

    protected function makeExistingKeyBackup(): bool
    {
        $madeBackup = false;
        $legacySubscriptionFileExists = file_exists(SubscriptionKeyGetService::LEGACY_SUBSCRIPTION_FILE);
        $legacySubscriptionFileIsWritable = is_writable(SubscriptionKeyGetService::LEGACY_SUBSCRIPTION_FILE);
        $tempFile1 = TMP . 'temp_subscription_file1';

        if ($legacySubscriptionFileExists) {
            if (!$legacySubscriptionFileIsWritable) {
                $this->fail('The following file is not writable ' . SubscriptionKeyGetService::LEGACY_SUBSCRIPTION_FILE);
            }
            rename(SubscriptionKeyGetService::LEGACY_SUBSCRIPTION_FILE, $tempFile1);
            $madeBackup = true;
        }

        $newSubscriptionFileExists = file_exists(SubscriptionKeyGetService::SUBSCRIPTION_FILE);
        $newSubscriptionFileIsWritable = is_writable(SubscriptionKeyGetService::SUBSCRIPTION_FILE);
        $tempFile2 = TMP . 'temp_subscription_file2';

        if ($newSubscriptionFileExists) {
            if (!$newSubscriptionFileIsWritable) {
                $this->fail('The following file is not writable ' . SubscriptionKeyGetService::SUBSCRIPTION_FILE);
            }
            rename(SubscriptionKeyGetService::SUBSCRIPTION_FILE, $tempFile2);
            $madeBackup = true;
        }

        return $madeBackup;
    }

    protected function restoreExistingKeyBackup(): void
    {
        $tempFile1 = TMP . 'temp_subscription_file1';
        $tempFile2 = TMP . 'temp_subscription_file2';

        if (file_exists($tempFile1)) {
            rename($tempFile1, SubscriptionKeyGetService::LEGACY_SUBSCRIPTION_FILE);
        }
        if (file_exists($tempFile2)) {
            rename($tempFile2, SubscriptionKeyGetService::SUBSCRIPTION_FILE);
        }
    }

    protected function getDummyUserMock(bool $isAdmin = false): UserAccessControl
    {
        $uac = $this->createMock(UserAccessControl::class);
        $uac->method('getId')->willReturn(UuidFactory::uuid());
        $uac->method('isAdmin')->willReturn($isAdmin);

        return $uac;
    }

    protected function getDummyAdminUACMock(): UserAccessControl
    {
        return $this->getDummyUserMock(true);
    }

    public function assertSubscriptionExists()
    {
        $this->assertSame(1, $this->Subscriptions->find()->all()->count());
    }

    public function assertSubscriptionDoesNotExist()
    {
        $this->assertSame(0, $this->Subscriptions->find()->all()->count());
    }

    public function persistSubscription(string $keyFileName): EntityInterface
    {
        $uac = $this->getDummyAdminUACMock();
        $SubscriptionsTable = TableRegistry::getTableLocator()->get('Passbolt/Subscription.Subscriptions');

        $entity = $SubscriptionsTable->newEntity(
            [
                'value' => $this->getAsciiKey($keyFileName),
            ],
            [
                'validate' => 'parent',
            ]
        );

        return $SubscriptionsTable->saveOrFail($entity, compact('uac'));
    }

    public function getAsciiKey(string $keyFileName): string
    {
        $filePath = $this->baseTestPath . 'Fixture' . DS . 'subscription' . DS . $keyFileName;
        if (!file_exists($filePath)) {
            throw new RuntimeException('Cannot find file ' . $filePath);
        }

        return file_get_contents($filePath);
    }

    public function persistValidSubscription(): EntityInterface
    {
        return $this->persistSubscription('subscription_dev');
    }

    public function persistInvalidSubscription(): EntityInterface
    {
        return $this->persistSubscription('subscription_issuer_ada');
    }

    public function persistExpiredSubscription(): EntityInterface
    {
        return $this->persistSubscription('subscription_expired');
    }
}
