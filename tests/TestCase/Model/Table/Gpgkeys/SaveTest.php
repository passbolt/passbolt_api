<?php
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
 * @since         2.0.0
 */

namespace App\Test\TestCase\Model\Table\Gpgkeys;

use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\I18n\Date;
use Cake\I18n\FrozenTime;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;

class SaveTest extends AppTestCase
{
    public $Gpgkeys;

    public $fixtures = ['app.Base/Users', 'app.Base/Gpgkeys'];

    public function setUp()
    {
        parent::setUp();
        $this->Gpgkeys = TableRegistry::getTableLocator()->get('Gpgkeys');
    }

    public function tearDown()
    {
        unset($this->Gpgkeys);
        parent::tearDown();
    }

    public function testGpgkeysValidationEmptyError()
    {
        $gpgkey = $this->Gpgkeys->newEntity([]);
        $errors = $gpgkey->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotEmpty($errors['user_id']['_required']);
        $this->assertNotEmpty($errors['armored_key']['_required']);
        $this->assertNotEmpty($errors['bits']['_required']);
        $this->assertNotEmpty($errors['fingerprint']['_required']);
        $this->assertNotEmpty($errors['key_id']['_required']);

        // allowed to be empty
        $this->assertFalse(isset($errors['id']));
        $this->assertFalse(isset($errors['expires']));
        $this->assertFalse(isset($errors['uid']));
    }

    public function testGpgkeysValidationCustomError()
    {
        $data = [
            'fingerprint' => 'test',
            'key_id' => 'test',
            'type' => 'test'
        ];
        $gpgkey = $this->Gpgkeys->newEntity($data);
        $errors = $gpgkey->getErrors();
        $this->assertNotEmpty($errors);
        foreach ($data as $field => $value) {
            $this->assertNotEmpty($errors[$field], 'There should be an error for field ' . $field);
            $this->assertNotEmpty($errors[$field]['custom']);
        }
    }

    public function testGpgkeysValidationFingerprint()
    {
        $fails = [
            'integer' => 3,
            'floats' => 3.5,
            'short string' => 'AF',
            'out of range char' => '03F60E958F4CB29723ACDF761353B5B15D9B054Z',
            'out of range emoji' => '03F60E958F4CB29723ACDF761353B5B15D9B054🔥',
            'extra space' => '03F6 0E95 8F4C B297 23AC  DF76 1353 B5B1 5D9B 054F'
        ];
        foreach ($fails as $case => $value) {
            $this->assertFalse(
                $this->Gpgkeys->isValidFingerprintRule($value),
                'Fingerprint validation should fail for case ' . $case
            );
        }

        $this->assertTrue($this->Gpgkeys->isValidFingerprintRule('03F60E958F4CB29723ACDF761353B5B15D9B054A'));
    }

    public function testGpgkeysValidationKeyId()
    {
        $fails = [
            'integer' => 3,
            'floats' => 3.5,
            'short string' => 'AF',
            'out of range char' => '03F60E9Z',
            'out of range emoji' => '03F60EE🔥'
        ];
        foreach ($fails as $case => $value) {
            $this->assertFalse(
                $this->Gpgkeys->isValidKeyIdRule($value),
                'Fingerprint validation should fail for case ' . $case
            );
        }

        $this->assertTrue($this->Gpgkeys->isValidKeyIdRule('03F60E95'));
    }

    public function testGpgkeysValidationKeyType()
    {
        $fails = [
            'integer' => 3,
            'floats' => 3.5,
            'short string' => 'AFZ',
            'short string emoji' => '🔥🔥🔥'
        ];
        foreach ($fails as $case => $value) {
            $this->assertFalse(
                $this->Gpgkeys->isValidKeyTypeRule($value),
                'Fingerprint validation should fail for case ' . $case
            );
        }

        $success = ['RSA', 'DSA', 'ECC', 'ELGAMAL', 'ECDSA', 'DH'];
        foreach ($success as $i => $case) {
            $this->assertTrue(
                $this->Gpgkeys->isValidKeyTypeRule($case),
                'Key type should work for case ' . $case
            );
        }
    }

    public function testGpgkeysValidationUidEmail()
    {
        $fails = [
            'integer' => 3,
            'floats' => 3.5,
            'short string' => 'AFZ',
            'email contains emoji' => 'uid (comment) <🔥@nope.com>'
        ];
        foreach ($fails as $case => $value) {
            $this->assertFalse(
                $this->Gpgkeys->uidContainValidEmailRule($value),
                'Uid email validation should fail for case ' . $case
            );
        }

        $success = [
            'ok' => 'test <this@fine.com>',
            'ok with comment' => 'test (comment) <this@fine.com>'
        ];
        foreach ($success as $case => $value) {
            $this->assertTrue(
                $this->Gpgkeys->uidContainValidEmailRule($value),
                'Uid email validation should work for case ' . $case
            );
        }
    }

    public function testGpgkeysValidationExpires()
    {
        $fails = [
            'yesterday' => FrozenTime::yesterday(),
            'now' => FrozenTime::now()
        ];
        foreach ($fails as $case => $value) {
            $this->assertFalse(
                $this->Gpgkeys->isInFutureRule($value),
                'Gpgkey expires date should not validate for case ' . $case
            );
        }

        $successes = [
            'tomorrow' => FrozenTime::tomorrow(),
            'tomorrow as time' => Date::tomorrow(),
            'way later' => FrozenTime::createFromDate('2030')
        ];
        foreach ($successes as $case => $value) {
            $this->assertTrue(
                $this->Gpgkeys->isInFutureRule($value),
                'Gpgkey expires date should validate for case ' . $case
            );
        }
    }

    public function testGpgkeysValidationIsInFuturePast()
    {
        $fails = [
            'future' => FrozenTime::createFromDate('2030'),
            'more than half a day' => Time::now()->modify('+13 hours'),
            'tomorrow as time' => Time::now()->modify('+24 hours'),
        ];
        foreach ($fails as $case => $value) {
            $this->assertFalse(
                $this->Gpgkeys->isInFuturePastRule($value),
                'Gpgkey created date should not validate for case ' . $case
            );
        }

        $successes = [
            'yesterday' => FrozenTime::yesterday(),
            'now' => FrozenTime::now(),
            'almost half a day' => Time::now()->modify('+11 hours'),
        ];
        foreach ($successes as $case => $value) {
            $this->assertTrue(
                $this->Gpgkeys->isInFuturePastRule($value),
                'Gpgkey created date should validate for case ' . $case
            );
        }
    }

    public function testGpgkeysValidationisParsableArmoredPublicKey()
    {
        $armoredKey = file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_public.key');
        $this->assertTrue($this->Gpgkeys->isParsableArmoredPublicKey($armoredKey));

        $armoredKeySplit = str_split($armoredKey, 300);
        $this->assertFalse($this->Gpgkeys->isParsableArmoredPublicKey($armoredKeySplit[0]));

        $armoredKeyCorrupt = str_replace('F', '0', $armoredKey);
        $this->assertFalse($this->Gpgkeys->isParsableArmoredPublicKey($armoredKeyCorrupt));
    }

    public function testGpgkeysRulesUniqueFingerprint()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $armoredKey = file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_public.key');

        $k = $this->Gpgkeys->buildEntityFromArmoredKey($armoredKey, $userId);
        $this->Gpgkeys->save($k);
        $error = $k->getErrors();
        $this->assertNotEmpty($error);
        $this->assertNotEmpty($error['fingerprint']['_isUnique']);
    }
}
