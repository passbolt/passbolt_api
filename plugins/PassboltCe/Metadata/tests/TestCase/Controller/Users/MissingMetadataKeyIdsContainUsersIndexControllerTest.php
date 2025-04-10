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

use App\Database\Type\ISOFormatDateTimeType;
use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use Cake\Core\Configure;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataPrivateKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

/**
 * @covers \App\Controller\Users\UsersIndexController
 */
class MissingMetadataKeyIdsContainUsersIndexControllerTest extends AppIntegrationTestCaseV5
{
    use LocatorAwareTrait;
    use GpgMetadataKeysTestTrait;

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

    public function testMissingMetadataKeyIdsContainUsersIndexController_Success()
    {
        $activeUser1 = UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->validFingerprint())->user()->active()->persist();
        $activeUser2 = UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->validFingerprint())->user()->active()->persist();
        $disabled = UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->validFingerprint())->user()->disabled()->persist();
        $admin = UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->validFingerprint())->admin()->active()->persist();
        UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->validFingerprint())->user()->deleted()->persist();
        UserFactory::make()->user()->inactive()->persist();

        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier($admin)->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUserPrivateKey($activeUser1->get('gpgkey'))->persist();
        // no metadata private key for $activeUser2
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUserPrivateKey($disabled->get('gpgkey'))->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUserPrivateKey($admin->get('gpgkey'))->persist();
        // deleted keys shouldn't be returned
        MetadataKeyFactory::make()->withCreatorAndModifier($admin)->deleted()->withServerPrivateKey()->persist();

        $this->logInAs($admin);
        $queryParams = http_build_query(['contain' => ['missing_metadata_key_ids' => true]]);
        $this->getJson("/users.json?{$queryParams}");

        $this->assertSuccess();
        $responseArray = $this->getResponseBodyAsArray();
        foreach ($responseArray as $response) {
            if ($response['id'] === $activeUser2->get('id')) {
                $this->assertNotEmpty($response['missing_metadata_key_ids']);
                $this->assertEqualsCanonicalizing([$metadataKey->get('id')], $response['missing_metadata_key_ids']);
            } else {
                $this->assertEmpty($response['missing_metadata_key_ids']);
            }
        }
    }

    public function testMissingMetadataKeyIdsContainUsersIndexController_Success_QueryParamNotSet()
    {
        $activeUser1 = UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->validFingerprint())->user()->active()->persist();
        UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->validFingerprint())->user()->active()->persist();
        $disabled = UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->validFingerprint())->user()->disabled()->persist();
        $admin = UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->validFingerprint())->admin()->active()->persist();

        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier($admin)->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUserPrivateKey($activeUser1->get('gpgkey'))->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUserPrivateKey($disabled->get('gpgkey'))->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUserPrivateKey($admin->get('gpgkey'))->persist();
        // no metadata private key for $activeUser2

        $this->logInAs($admin);
        $this->getJson('/users.json');

        $this->assertSuccess();
        $responseArray = $this->getResponseBodyAsArray();
        foreach ($responseArray as $response) {
            $this->assertArrayNotHasKey('missing_metadata_key_ids', $response);
        }
    }

    public function testMissingMetadataKeyIdsContainUsersIndexController_Success_ContainNotPresentIfV5Disabled()
    {
        Configure::write('passbolt.v5.enabled', false);
        $activeUser1 = UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->validFingerprint())->user()->active()->persist();
        $admin = UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->validFingerprint())->admin()->active()->persist();

        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier($admin)->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUserPrivateKey($activeUser1->get('gpgkey'))->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUserPrivateKey($admin->get('gpgkey'))->persist();

        $this->logInAs($admin);
        $queryParams = http_build_query(['contain' => ['missing_metadata_key_ids' => true]]);
        $this->getJson("/users.json?{$queryParams}");

        $this->assertSuccess();
        $responseArray = $this->getResponseBodyAsArray();
        foreach ($responseArray as $response) {
            $this->assertArrayNotHasKey('missing_metadata_key_ids', $response);
        }
    }

    public function testMissingMetadataKeyIdsContainUsersIndexController_Success_EmptyResultNoMissingKeys()
    {
        $activeUser1 = UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->validFingerprint())->user()->active()->persist();
        $admin = UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->validFingerprint())->admin()->active()->persist();

        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier($admin)->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUserPrivateKey($activeUser1->get('gpgkey'))->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUserPrivateKey($admin->get('gpgkey'))->persist();

        $this->logInAs($admin);
        $queryParams = http_build_query(['contain' => ['missing_metadata_key_ids' => true]]);
        $this->getJson("/users.json?{$queryParams}");

        $this->assertSuccess();
        $responseArray = $this->getResponseBodyAsArray();
        foreach ($responseArray as $response) {
            $this->assertArrayHasKey('missing_metadata_key_ids', $response);
            $this->assertIsArray($response['missing_metadata_key_ids']);
            $this->assertEmpty($response['missing_metadata_key_ids']);
        }
    }

    public function testMissingMetadataKeyIdsContainUsersIndexController_Success_NoKeysSet()
    {
        UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->validFingerprint())->user()->active()->persist();
        $admin = UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->validFingerprint())->admin()->active()->persist();

        $this->logInAs($admin);
        $queryParams = http_build_query(['contain' => ['missing_metadata_key_ids' => true]]);
        $this->getJson("/users.json?{$queryParams}");

        $this->assertSuccess();
        $responseArray = $this->getResponseBodyAsArray();
        foreach ($responseArray as $response) {
            $this->assertArrayHasKey('missing_metadata_key_ids', $response);
            $this->assertIsArray($response['missing_metadata_key_ids']);
            $this->assertEmpty($response['missing_metadata_key_ids']);
        }
    }

    public function testMissingMetadataKeyIdsContainUsersIndexController_Success_ReturnsExpireKeys()
    {
        $activeUser1 = UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->validFingerprint())->user()->active()->persist();
        $admin = UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->validFingerprint())->admin()->active()->persist();

        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier($admin)->expired()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUserPrivateKey($activeUser1->get('gpgkey'))->persist();
        // missing for admin themselves

        $this->logInAs($admin);
        $queryParams = http_build_query(['contain' => ['missing_metadata_key_ids' => true]]);
        $this->getJson("/users.json?{$queryParams}");

        $this->assertSuccess();
        $responseArray = $this->getResponseBodyAsArray();
        foreach ($responseArray as $response) {
            $this->assertArrayHasKey('missing_metadata_key_ids', $response);
            if ($response['id'] === $admin->get('id')) {
                $this->assertCount(1, $response['missing_metadata_key_ids']);
                $this->assertSame($metadataKey->get('id'), $response['missing_metadata_key_ids'][0]);
            }
        }
    }

    public function testMissingMetadataKeyIdsContainUsersIndexController_Error_UserIsNotAdmin()
    {
        $this->logInAsUser();
        $queryParams = http_build_query(['contain' => ['missing_metadata_key_ids' => true]]);
        $this->getJson("/users.json?{$queryParams}");

        $this->assertError(403);
        // Reset state to not failure subsequent tests
        ISOFormatDateTimeType::remapDatetimeTypesToDefault();
    }

    public function testMissingMetadataKeyIdsContainUsersIndexController_Error_InvalidContainValue()
    {
        $this->logInAsAdmin();
        $queryParams = http_build_query(['contain' => ['missing_metadata_key_ids' => 'foo-bar']]);
        $this->getJson("/users.json?{$queryParams}");

        $this->assertBadRequestError('Invalid contain');
    }
}
