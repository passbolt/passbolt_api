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
 * @since         4.10.0
 */

namespace Passbolt\Metadata\TestCase\Service;

use App\Error\Exception\CustomValidationException;
use App\Error\Exception\FormValidationException;
use App\Service\OpenPGP\OpenPGPCommonUserOperationsTrait;
use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCaseV5;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ConflictException;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\FrozenTime;
use Passbolt\Metadata\Service\MetadataSessionKeyUpdateService;
use Passbolt\Metadata\Test\Factory\MetadataSessionKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

/**
 * @covers \Passbolt\Metadata\Service\MetadataSessionKeyUpdateService
 */
class MetadataSessionKeyUpdateServiceTest extends AppTestCaseV5
{
    use GpgMetadataKeysTestTrait;
    use OpenPGPCommonUserOperationsTrait;

    /**
     * @var MetadataSessionKeyUpdateService|null
     */
    private ?MetadataSessionKeyUpdateService $service = null;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new MetadataSessionKeyUpdateService();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);

        parent::tearDown();
    }

    public function testMetadataSessionKeyUpdateService_Success(): void
    {
        FrozenTime::setTestNow('2021-01-31 22:11:30');
        $key = GpgkeyFactory::make()->withAdaKey();
        $user = UserFactory::make()->with('Gpgkeys', $key)->active()->persist();
        $sessionKey = MetadataSessionKeyFactory::make()->withUser($user)->persist();
        $sessionKey = MetadataSessionKeyFactory::get($sessionKey->get('id')); // needed to get exact modified time
        $oldModified = new FrozenTime($sessionKey->get('modified'));
        $uac = $this->makeUac($user);
        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setEncryptKeyWithUserKey($gpg, $user->get('gpgkey'));
        $msg = $gpg->encrypt(MetadataSessionKeyFactory::getCleartextDataJson());
        $data = [
            'modified' => $oldModified,
            'data' => $msg,
        ];
        FrozenTime::setTestNow(null);
        $this->service->update($uac, $sessionKey->get('id'), $data);

        $this->assertEquals(1, MetadataSessionKeyFactory::count());
        $updatedSessionKey = MetadataSessionKeyFactory::get($sessionKey->get('id'));
        $this->assertNotEquals($updatedSessionKey->get('data'), $sessionKey->get('data'));

        // modified time was updated
        $newModified = new FrozenTime($updatedSessionKey->get('modified'));
        $this->assertTrue($newModified->greaterThan($oldModified));
    }

    public function testMetadataSessionKeyUpdateService_Error_NoUpdate(): void
    {
        $key = GpgkeyFactory::make()->withAdaKey();
        $user = UserFactory::make()->with('Gpgkeys', $key)->active()->persist();
        $sessionKey = MetadataSessionKeyFactory::make()->withUser($user)->persist();
        $sessionKey = MetadataSessionKeyFactory::get($sessionKey->get('id')); // needed to get exact modified time
        $oldModified = new FrozenTime($sessionKey->get('modified'));
        $uac = $this->makeUac($user);
        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setEncryptKeyWithUserKey($gpg, $user->get('gpgkey'));
        $msg = $gpg->encrypt(MetadataSessionKeyFactory::getCleartextDataJson());
        $data = [
            'modified' => $oldModified,
            'data' => $sessionKey->get('data'),
        ];

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The metadata session key data is identical.');

        $this->service->update($uac, $sessionKey->get('id'), $data);
    }

    public function testMetadataSessionKeyUpdateService_Error_TimeInTheFuture(): void
    {
        $key = GpgkeyFactory::make()->withAdaKey();
        $user = UserFactory::make()->with('Gpgkeys', $key)->active()->persist();
        $sessionKey = MetadataSessionKeyFactory::make()->withUser($user)->persist();
        $sessionKey = MetadataSessionKeyFactory::get($sessionKey->get('id')); // needed to get exact modified time
        $uac = $this->makeUac($user);
        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setEncryptKeyWithUserKey($gpg, $user->get('gpgkey'));
        $msg = $gpg->encrypt(MetadataSessionKeyFactory::getCleartextDataJson());
        $data = [
            'modified' => FrozenTime::now(),
            'data' => $msg,
        ];

        $this->expectException(ConflictException::class);
        $this->expectExceptionMessage('The metadata session key data has changed.');

        $this->service->update($uac, $sessionKey->get('id'), $data);
    }

    public function testMetadataSessionKeyUpdateService_Error_TimeInThePast(): void
    {
        $key = GpgkeyFactory::make()->withAdaKey();
        $user = UserFactory::make()->with('Gpgkeys', $key)->active()->persist();
        $sessionKey = MetadataSessionKeyFactory::make()->withUser($user)->persist();
        $sessionKey = MetadataSessionKeyFactory::get($sessionKey->get('id')); // needed to get exact modified time
        $uac = $this->makeUac($user);
        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setEncryptKeyWithUserKey($gpg, $user->get('gpgkey'));
        $msg = $gpg->encrypt(MetadataSessionKeyFactory::getCleartextDataJson());
        $data = [
            'modified' => FrozenTime::now()->modify('-3 years'),
            'data' => $msg,
        ];

        $this->expectException(ConflictException::class);
        $this->expectExceptionMessage('The metadata session key data has changed.');

        $this->service->update($uac, $sessionKey->get('id'), $data);
    }

    public function testMetadataSessionKeyUpdateService_Error_InvalidId(): void
    {
        $user = UserFactory::make()->admin()->active()->persist();
        $uac = $this->makeUac($user);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The metadata session key identifier should be a UUID.');

        $this->service->update($uac, '🔥', MetadataSessionKeyFactory::getDefaultData());
    }

    public function testMetadataSessionKeyUpdateService_Error_InvalidData(): void
    {
        $user = UserFactory::make()->admin()->active()->persist();
        $sessionKey = MetadataSessionKeyFactory::make()->withUser($user)->persist();
        $uac = $this->makeUac($user);

        $this->expectException(FormValidationException::class);
        $this->expectExceptionMessage('Could not validate the data.');

        $this->service->update($uac, $sessionKey->get('id'), ['something' => 'else']);
    }

    public function testMetadataSessionKeyUpdateService_Error_SessionKeyDoesNotBelongsToCurrentUser(): void
    {
        $sessionKeys = MetadataSessionKeyFactory::make(1)->withMakiSessionKey()->persist();
        $uac = $this->makeUac(UserFactory::make()->active()->persist());

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('The metadata session key does not exist or does not belong to this user.');

        $this->service->update($uac, $sessionKeys->get('id'), MetadataSessionKeyFactory::getDefaultData());
    }

    public function testMetadataSessionKeyUpdateService_ErrorWrongUserKey(): void
    {
        FrozenTime::setTestNow('2021-01-31 22:11:30');
        $key = GpgkeyFactory::make()->withAdaKey();
        $user = UserFactory::make()->with('Gpgkeys', $key)->active()->persist();
        $sessionKey = MetadataSessionKeyFactory::make()->withUser($user)->persist();
        $sessionKey = MetadataSessionKeyFactory::get($sessionKey->get('id')); // needed to get exact modified time
        $oldModified = new FrozenTime($sessionKey->get('modified'));
        $uac = $this->makeUac($user);

        $betty = UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->withBettyKey())->persist();
        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setEncryptKeyWithUserKey($gpg, $betty->get('gpgkey'));
        $msg = $gpg->encrypt(MetadataSessionKeyFactory::getCleartextDataJson());
        $data = [
            'modified' => $oldModified,
            'data' => $msg,
        ];
        FrozenTime::setTestNow(null);

        $this->expectException(CustomValidationException::class);
        $this->expectExceptionMessage('The metadata session key could not be saved.');
        $this->service->update($uac, $sessionKey->get('id'), $data);
    }
}
