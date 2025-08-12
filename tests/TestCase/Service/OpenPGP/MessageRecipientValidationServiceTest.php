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
 * @since         5.4.0
 */
namespace App\Test\TestCase\Service\OpenPGP;

use App\Error\Exception\CustomValidationException;
use App\Service\OpenPGP\MessageRecipientValidationService;
use App\Service\OpenPGP\MessageValidationService;
use App\Service\OpenPGP\PublicKeyValidationService;
use App\Test\Lib\AppTestCase;
use App\Utility\OpenPGP\OpenPGPBackendFactory;

class MessageRecipientValidationServiceTest extends AppTestCase
{
    public function testMessageRecipientValidationService_Success()
    {
        $armoredMessage = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'Messages' . DS . 'ada_for_betty_signed.msg');
        $messageInfo = MessageValidationService::parseAndValidateMessage($armoredMessage);
        $armoredKey = file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'betty_public.key');
        $keyInfo = PublicKeyValidationService::parseAndValidatePublicKey($armoredKey);
        $service = new MessageRecipientValidationService();
        $this->assertTrue($service->isMessageForRecipient($messageInfo, $keyInfo));
    }

    public function testMessageRecipientValidationService_SuccessNoSubKey()
    {
        $armoredKey = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'no_subkey.key');
        $keyInfo = PublicKeyValidationService::parseAndValidatePublicKey($armoredKey);
        $gpg = OpenPGPBackendFactory::get();
        $gpg->setEncryptKey($armoredKey);
        $msg = $gpg->encrypt('test');
        $messageInfo = MessageValidationService::parseAndValidateMessage($msg);
        $service = new MessageRecipientValidationService();
        $this->assertTrue($service->isMessageForRecipient($messageInfo, $keyInfo));
    }

    public function testMessageRecipientValidationService_ErrorNoRecipient()
    {
        $service = new MessageRecipientValidationService();
        $this->expectException(CustomValidationException::class);
        $service->isMessageForRecipient([], []);
    }

    public function testMessageRecipientValidationService_ErrorNotForRecipient()
    {
        $service = new MessageRecipientValidationService();
        $this->assertFalse($service->isMessageForRecipient(['recipients' => [0]], []));
        $this->assertFalse($service->isMessageForRecipient(['recipients' => ['invalid']], []));
        $this->assertFalse($service->isMessageForRecipient(['recipients' => ['invalid']], ['subkeys' => ['invalid']]));
        $this->assertFalse($service->isMessageForRecipient(['recipients' => ['invalid']], ['subkeys' => []]));
    }
}
