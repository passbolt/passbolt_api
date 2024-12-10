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

namespace Passbolt\Metadata\Test\TestCase\Controller\Users;

use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use Cake\Core\Configure;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataPrivateKeyFactory;

/**
 * @covers \App\Controller\Users\UsersViewController
 */
class MissingMetadataKeyIdsContainUsersViewControllerTest extends AppIntegrationTestCaseV5
{
    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        RoleFactory::make()->guest()->persist();
        // enable event tracking
        EventManager::instance()->setEventList(new EventList());
    }

    public function testMissingMetadataKeyIdsContainUsersViewController_Success_UserId(): void
    {
        $this->disableErrorHandlerMiddleware();
        $admin = UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->validFingerprint())->admin()->active()->persist();
        $user = UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->validFingerprint())->user()->active()->persist();
        // metadata key
        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier($admin)->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUserPrivateKey($admin->get('gpgkey'))->persist();

        $this->logInAs($admin);
        $queryParams = http_build_query(['contain' => ['missing_metadata_key_ids' => true]]);
        $this->getJson("/users/{$user->get('id')}.json?{$queryParams}");

        $this->assertSuccess();
        $responseArray = $this->getResponseBodyAsArray();
        $this->assertArrayHasKey('missing_metadata_key_ids', $responseArray);
        $this->assertCount(1, $responseArray['missing_metadata_key_ids']);
        $this->assertEqualsCanonicalizing([$metadataKey->get('id')], $responseArray['missing_metadata_key_ids']);
    }

    public function testMissingMetadataKeyIdsContainUsersViewController_Success_Me(): void
    {
        $this->disableErrorHandlerMiddleware();
        $admin = UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->validFingerprint())->admin()->active()->persist();
        $user = UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->validFingerprint())->user()->active()->persist();
        // metadata key
        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier($admin)->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUserPrivateKey($admin->get('gpgkey'))->persist();

        $this->logInAs($user);
        $queryParams = http_build_query(['contain' => ['missing_metadata_key_ids' => true]]);
        $this->getJson("/users/me.json?{$queryParams}");

        $this->assertSuccess();
        $responseArray = $this->getResponseBodyAsArray();
        $this->assertArrayHasKey('missing_metadata_key_ids', $responseArray);
        $this->assertCount(1, $responseArray['missing_metadata_key_ids']);
        $this->assertEqualsCanonicalizing([$metadataKey->get('id')], $responseArray['missing_metadata_key_ids']);
    }

    public function testMissingMetadataKeyIdsContainUsersViewController_Success_ContainNotPresentIfV5Disabled()
    {
        Configure::write('passbolt.v5.enabled', false);
        $admin = UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->validFingerprint())->admin()->active()->persist();
        $user = UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->validFingerprint())->user()->active()->persist();
        // metadata key
        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier($admin)->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUserPrivateKey($admin->get('gpgkey'))->persist();

        $this->logInAs($user);
        $queryParams = http_build_query(['contain' => ['missing_metadata_key_ids' => true]]);
        $this->getJson("/users/me.json?{$queryParams}");

        $this->assertSuccess();
        $responseArray = $this->getResponseBodyAsArray();
        $this->assertArrayNotHasKey('missing_metadata_key_ids', $responseArray);
    }

    public function testMissingMetadataKeyIdsContainUsersIndexController_Error_UserTriesToAccessDifferentUsersData()
    {
        $admin = UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->validFingerprint())->admin()->active()->persist();
        $this->logInAsUser();
        $queryParams = http_build_query(['contain' => ['missing_metadata_key_ids' => true]]);
        $this->getJson("/users/{$admin->get('id')}.json?{$queryParams}");
        $this->assertError(403);
    }
}
