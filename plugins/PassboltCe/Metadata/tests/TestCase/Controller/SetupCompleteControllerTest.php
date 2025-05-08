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
namespace Passbolt\Metadata\Test\TestCase\Controller;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataKeysSettingsFactory;
use Passbolt\Metadata\Test\Factory\MetadataPrivateKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

class SetupCompleteControllerTest extends AppIntegrationTestCaseV5
{
    use GpgMetadataKeysTestTrait;

    public function testMetadataSetupCompleteController_Success(): void
    {
        MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        MetadataKeysSettingsFactory::make()->persist();

        /** @var \App\Model\Entity\AuthenticationToken $t */
        $t = AuthenticationTokenFactory::make()
            ->active()
            ->type(AuthenticationToken::TYPE_REGISTER)
            ->with('Users', UserFactory::make()->admin()->inactive())
            ->persist();
        $user = $t->user;
        $url = '/setup/complete/' . $user->id . '.json';
        $armoredKey = file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_public.key');
        $data = [
            'authentication_token' => [
                'token' => $t->token,
            ],
            'gpgkey' => [
                'armored_key' => $armoredKey,
            ],
        ];
        $this->postJson($url, $data);
        $this->assertSuccess();

        /** @var \Passbolt\Metadata\Model\Entity\MetadataPrivateKey $privateKey */
        $privateKey = MetadataPrivateKeyFactory::find()->where(['user_id IS' => $user->id])->firstOrFail();
        $gpg = OpenPGPBackendFactory::get();
        $key = FIXTURES . DS . 'Gpgkeys' . DS . 'ada_private_nopassphrase.key';
        $fingerprint = $gpg->importKeyIntoKeyring(file_get_contents($key));
        $gpg->setDecryptKeyFromFingerprint($fingerprint, '');
        $json = $gpg->decrypt($privateKey->data);
        $privateKeyDto = json_decode($json, true, 2, JSON_THROW_ON_ERROR);
        $this->assertEquals($this->getValidPrivateKeyCleartext(), $privateKeyDto);
        $this->assertSame($user->id, $privateKey->created_by);
        $this->assertSame($user->id, $privateKey->modified_by);
    }
}
