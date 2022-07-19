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
 * @since         2.0.0
 */

namespace App\Test\TestCase\Model\Table\Gpgkeys;

use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\I18n\FrozenDate;
use Cake\I18n\FrozenTime;
use Cake\ORM\TableRegistry;

class SaveTest extends AppTestCase
{
    public $Gpgkeys;

    public $fixtures = ['app.Base/Users', 'app.Base/Gpgkeys'];

    public function setUp(): void
    {
        parent::setUp();
        $this->Gpgkeys = TableRegistry::getTableLocator()->get('Gpgkeys');
    }

    public function tearDown(): void
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
            'type' => 'test',
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
            'short string' => 'AF',
            'out of range char' => '03F60E958F4CB29723ACDF761353B5B15D9B054Z',
            'out of range emoji' => '03F60E958F4CB29723ACDF761353B5B15D9B054🔥',
            'extra space' => '03F6 0E95 8F4C B297 23AC  DF76 1353 B5B1 5D9B 054F',
        ];
        foreach ($fails as $case => $fingerprint) {
            $entity = $this->Gpgkeys->newEntity(compact('fingerprint'));
            $this->Gpgkeys->save($entity);

            $this->assertSame(
                'The fingerprint should be a string of 40 hexadecimal characters.',
                $entity->getError('fingerprint')['custom']
            );
            $this->assertTrue($entity->hasErrors(), 'Fingerprint validation should fail for case ' . $case);
        }

        $fingerprint = '03F60E958F4CB29723ACDF761353B5B15D9B054A';
        $entity = $this->Gpgkeys->newEntity(compact('fingerprint'));
        $this->Gpgkeys->save($entity);
        $this->assertEmpty($entity->getError('fingerprint'));
    }

    public function testGpgkeysValidationKeyId()
    {
        $fails = [
            'short string' => 'AF',
            'out of range char' => '03F60E9Z',
            'out of range emoji' => '03F60EE🔥',
        ];
        foreach ($fails as $case => $value) {
            $entity = $this->Gpgkeys->newEntity(['key_id' => $value]);
            $this->Gpgkeys->save($entity);
            $this->assertSame(
                'The key identifier should be a string of 8 hexadecimal characters.',
                $entity->getError('key_id')['custom']
            );
            $this->assertTrue($entity->hasErrors(), 'Key ID validation should fail for case ' . $case);
        }

        $entity = $this->Gpgkeys->newEntity(['key_id' => '03F60E95']);
        $this->Gpgkeys->save($entity);

        $this->assertEmpty($entity->getError('key_id'));
    }

    public function testGpgkeysValidationKeyType()
    {
        $fails = [
            'short string' => 'AFZ',
            'short string emoji' => '🔥🔥🔥',
        ];
        foreach ($fails as $case => $value) {
            $entity = $this->Gpgkeys->newEntity(['type' => $value]);
            $this->Gpgkeys->save($entity);
            $this->assertSame(
                'The type should be one of the following: RSA, ECC, ECDSA, DH.',
                $entity->getError('type')['custom'],
                'Gpg Key validation should fail for case ' . $case
            );
        }

        $success = ['RSA', 'ELGAMAL', 'DSA', 'ECC', 'ECDSA', 'DH', 'EdDSA'];
        foreach ($success as $type) {
            $entity = $this->Gpgkeys->newEntity(compact('type'));
            $this->Gpgkeys->save($entity);

            $this->assertEmpty($entity->getError('type'), 'Key type should work for case ' . $type);
        }
    }

    public function testGpgkeysValidationExpires()
    {
        $fails = [
            'yesterday' => FrozenTime::yesterday(),
            'now' => FrozenTime::now(),
        ];
        foreach ($fails as $case => $value) {
            $entity = $this->Gpgkeys->newEntity(['expires' => $value]);
            $this->Gpgkeys->save($entity);

            $this->assertSame(
                'The key should not already be expired.',
                $entity->getError('expires')['custom']
            );
            $this->assertTrue(
                $entity->hasErrors(),
                'Gpgkeys expires date should not validate for case ' . $case
            );
        }

        $successes = [
            'tomorrow' => FrozenTime::tomorrow(),
            'tomorrow as time' => FrozenDate::tomorrow(),
            'way later' => FrozenTime::createFromDate(2030),
        ];
        foreach ($successes as $case => $value) {
            $entity = $this->Gpgkeys->newEntity(['expires' => $value]);
            $this->Gpgkeys->save($entity);

            $this->assertEmpty($entity->getError('expires'), 'Gpgkeys expires date should validate for case ' . $case);
        }
    }

    public function testGpgkeysValidationIsInFuturePast()
    {
        $fails = [
            'future' => FrozenTime::createFromDate(2030),
            'more than half a day' => FrozenTime::now()->modify('+13 hours'),
            'tomorrow as time' => FrozenTime::now()->modify('+24 hours'),
        ];
        foreach ($fails as $case => $value) {
            $entity = $this->Gpgkeys->newEntity(['key_created' => $value]);
            $this->Gpgkeys->save($entity);

            $this->assertSame(
                'The creation date should be set in the past.',
                $entity->getError('key_created')['custom']
            );
            $this->assertTrue($entity->hasErrors(), 'Gpgkeys created date should not validate for case ' . $case);
        }

        $successes = [
            'yesterday' => FrozenTime::yesterday(),
            'now' => FrozenTime::now(),
            'almost half a day' => FrozenTime::now()->modify('+11 hours'),
        ];
        foreach ($successes as $case => $value) {
            $entity = $this->Gpgkeys->newEntity(['key_created' => $value]);
            $this->Gpgkeys->save($entity);

            $this->assertEmpty($entity->getError('key_created'), 'Gpgkeys created date should validate for case ' . $case);
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

    public function testGpgkeysSaveECCSuccess()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $armoredKey = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'ecc_nistp521_public.key');

        $k = $this->Gpgkeys->buildEntityFromArmoredKey($armoredKey, $userId);
        $this->Gpgkeys->save($k);

        $k = $this->Gpgkeys->find()->where(['fingerprint' => 'AEE8E22ACFBF70527C1BD918F571FEB3B15105EE'])->firstOrFail();
        $this->assertEquals($k->type, 'ECDSA');
        $this->assertEquals($k->bits, '521');
    }

    public function testGpgkeysSaveECCSuccess2()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $armoredKey = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'ecc_curve25519_public.key');

        $k = $this->Gpgkeys->buildEntityFromArmoredKey($armoredKey, $userId);
        $this->Gpgkeys->save($k);

        $k = $this->Gpgkeys->find()->where(['fingerprint' => '21DB3781A35DFDA802A9B17557800F30009B7B46'])->firstOrFail();
        $this->assertEquals($k->type, 'EdDSA');
        $this->assertEquals($k->bits, '256');
    }

    public function testGpgkeysSaveECCSuccess3()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $armoredKey = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'ecc_brainpoolp384_public.key');

        $k = $this->Gpgkeys->buildEntityFromArmoredKey($armoredKey, $userId);
        $this->Gpgkeys->save($k);

        $k = $this->Gpgkeys->find()->where(['fingerprint' => 'AB78E1897CAF279A1A255DF63B5C02FB8C17837B'])->firstOrFail();
        $this->assertEquals($k->type, 'ECDSA');
        $this->assertEquals($k->bits, '384');
    }
}
