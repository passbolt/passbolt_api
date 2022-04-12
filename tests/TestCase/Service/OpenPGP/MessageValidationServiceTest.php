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
 * @since         3.6.0
 */
namespace App\Test\TestCase\Service\OpenPGP;

use App\Error\Exception\CustomValidationException;
use App\Service\OpenPGP\MessageValidationService;
use App\Service\OpenPGP\PublicKeyValidationService;
use App\Test\Lib\AppTestCase;
use Cake\Utility\Hash;

class MessageValidationServiceTest extends AppTestCase
{
    public function testMessageValidationService_GetDefaultRules_Success()
    {
        $this->assertNotEmpty(MessageValidationService::getDefaultRules());
    }

    public function testMessageValidationService_Parse_Success_AsymmetricSigned()
    {
        $armoredMessage = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'Messages' . DS . 'ada_for_betty_signed.msg');
        $messageInfo = MessageValidationService::parseAndValidateMessage($armoredMessage);
        $this->assertEquals(true, $messageInfo['asymmetric']);

        // check recipient contains betty subkey // 0D0FD7645E1E1C6D
        $armoredKey = file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'betty_public.key');
        $keyInfo = PublicKeyValidationService::parseAndValidatePublicKey($armoredKey);
        $this->assertEquals($messageInfo['recipients'], Hash::extract($keyInfo['sub_keys'], '{n}.key_id'));
        $this->assertEquals('0D0FD7645E1E1C6D', $messageInfo['recipients'][0]);
    }

    public function testMessageValidationService_Parse_Success_AsymmetricUnsigned()
    {
        $armoredMessage = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'Messages' . DS . 'ada_for_betty_unsigned.msg');
        $messageInfo = MessageValidationService::parseAndValidateMessage($armoredMessage, MessageValidationService::getAsymmetricMessageRules());
        $this->assertEquals(true, $messageInfo['asymmetric']);
        $this->assertEquals('0D0FD7645E1E1C6D', $messageInfo['recipients'][0]);
    }

    public function testMessageValidationService_Parse_Success_AsymmetricMultipleRecipients()
    {
        $armoredMessage = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'Messages' . DS . 'ada_for_betty_and_ada.msg');
        $messageInfo = MessageValidationService::parseAndValidateMessage($armoredMessage);
        $this->assertEquals(true, $messageInfo['asymmetric']);
        $this->assertEquals('0D0FD7645E1E1C6D', $messageInfo['recipients'][0]);

        // check recipient contains ada subkey // 53FDD1093524703E
        $armoredKey = file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_public.key');
        $keyInfo = PublicKeyValidationService::parseAndValidatePublicKey($armoredKey);
        $this->assertEquals([$messageInfo['recipients'][1]], Hash::extract($keyInfo['sub_keys'], '{n}.key_id'));
        $this->assertEquals('53FDD1093524703E', $messageInfo['recipients'][1]);
    }

    public function testMessageValidationService_Parse_Success_Symmetric()
    {
        $armoredMessage = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'Messages' . DS . 'symetric_secret_password.msg');
        $info = MessageValidationService::parseAndValidateMessage($armoredMessage);
        $this->assertEquals(true, $info['symmetric']);
        $this->assertEmpty($info['recipients']);

        $info = MessageValidationService::parseAndValidateMessage($armoredMessage, MessageValidationService::getSymmetricMessageRules());
        $this->assertEquals(true, $info['symmetric']);
        $this->assertEmpty($info['recipients']);
    }

    public function testMessageValidationService_Parse_Error_Symmetric()
    {
        $armoredMessage = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'Messages' . DS . 'symetric_secret_password.msg');
        try {
            MessageValidationService::parseAndValidateMessage($armoredMessage, MessageValidationService::getAsymmetricMessageRules());
            $this->fail();
        } catch (CustomValidationException $exception) {
            $errors = $exception->getErrors();
            $this->assertNotEmpty($errors['data']['hasAsymmetricPacketRule']);
        }
    }

    public function testMessageValidationService_Parse_Error_Asymmetric()
    {
        $armoredMessage = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'Messages' . DS . 'ada_for_betty_unsigned.msg');
        try {
            MessageValidationService::parseAndValidateMessage($armoredMessage, MessageValidationService::getSymmetricMessageRules());
            $this->fail();
        } catch (CustomValidationException $exception) {
            $errors = $exception->getErrors();
            $this->assertNotEmpty($errors['data']['hasSymmetricPacketRule']);
        }
    }

    public function testMessageValidationService_Parse_Error_Unparsable()
    {
        $armoredMessage = "-----BEGIN PGP MESSAGE-----

wcFBN5JnpRkE+rdSkjk+xU3xxwgAwPyQS6KvOPvoafPFWEKv
EgsqWorWgM0IBZJgvipMlWafafafafaUrkCHbMOzmR+tFf4O
=XDk
-----END PGP MESSAGE-----";

        try {
            MessageValidationService::parseAndValidateMessage($armoredMessage);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $errors = $exception->getErrors();
            $this->assertNotEmpty($errors['data']['isParsableArmoredMessageRule']);
        }
    }

    public function testMessageValidationService_HasRecipient_Success()
    {
        $armoredMessage = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'Messages' . DS . 'ada_for_betty_signed.msg');
        $info = MessageValidationService::parseAndValidateMessage($armoredMessage);
        $this->assertEquals(true, $info['asymmetric']);
        $this->assertEquals('0D0FD7645E1E1C6D', $info['recipients'][0]);
    }
}
