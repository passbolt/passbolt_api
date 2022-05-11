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
 * @since         2.0.0
 */

namespace Passbolt\Ee\Test\TestCase\Form;

use Cake\TestSuite\TestCase;
use Cake\Utility\Hash;
use Passbolt\Ee\Form\SubscriptionKeyAsciiForm;
use Passbolt\Ee\Test\Lib\DummySubscriptionTrait;

class SubscriptionKeyAsciiFormTest extends TestCase
{
    use DummySubscriptionTrait;

    protected $_licenseKeyForm;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->loadPlugins(['Passbolt/Ee' => []]);
        $this->_licenseKeyForm = new SubscriptionKeyAsciiForm();
        $this->setUpPathAndPublicSubscriptionKey();
    }

    public function testSubscriptionKeyAsciiForm_ParseSuccess()
    {
        $licenseStr = $this->getDummySubscriptionKey('subscription_dev');
        $this->_licenseKeyForm->setData(['key_ascii' => $licenseStr]);
        try {
            $licenseInfo = $this->_licenseKeyForm->parse();
        } catch (\Exception $e) {
            $this->fail('The license does not validate: ' . $e->getMessage());

            return null;
        }
        $expected = $this->getValidSubscription();
        $actual = $licenseInfo->toArray();

        $this->assertSame($expected['customer_id'], $actual['customer_id']);
        $this->assertSame($expected['subscription_id'], $actual['subscription_id']);
        $this->assertSame($expected['users'], $actual['users']);
        $this->assertSame($expected['created'], $actual['created']);
        $this->assertSame($expected['expiry'], $actual['expiry']);
        $this->assertSame($expected['email'], $actual['email']);
        $this->assertSame(trim($licenseStr), $actual['data']);
    }

    public function testSubscriptionKeyAsciiForm_Validate_ErrorInvalidFormat()
    {
        $licensesStr = [
            'empty license' => '',
            'not even a gpg message' => '---- invalid format ----',
            'corrupted gpg message' => 'sqSQSQsqsqqSQsqSQsqssqsqSQsq',
        ];

        foreach ($licensesStr as $licenseStr) {
            $data = ['key_ascii' => $licenseStr];
            $result = $this->_licenseKeyForm->execute($data);

            $this->assertFalse($result);
        }
    }

    public function testSubscriptionKeyAsciiForm_ErrorGetInfo_InvalidLicenseIssuer()
    {
        $licenseStr = $this->getDummySubscriptionKey('subscription_issuer_ada');
        $data = ['key_ascii' => $licenseStr];
        $result = $this->_licenseKeyForm->execute($data);
        $errors = $this->_licenseKeyForm->getErrors();

        $this->assertFalse($result);
        $this->assertTrue(isset($errors['key_ascii']));
        $this->assertNotEmpty(Hash::get($errors, 'key_ascii.is_valid_subscription'));
        $this->assertEquals(Hash::get($errors, 'key_ascii.is_valid_subscription'), 'The subscription content or signature is not valid.');
    }

    public function testSubscriptionKeyAsciiForm_Validate_Success()
    {
        $licenseStr = $this->getDummySubscriptionKey('subscription_dev');

        $data = ['key_ascii' => $licenseStr];
        $result = $this->_licenseKeyForm->execute($data);

        $this->assertTrue($result);
    }

    /**
     * Returns a string if an exception is expected
     *
     * @return array[]
     */
    public function dataForTestParse()
    {
        return [
            ['subscription_dev', $this->getValidSubscription()],
            ['subscription_expired', $this->getExpiredSubscription()],
            ['subscription_issuer_ada', \Exception::class],
        ];
    }

    /**
     * @dataProvider dataForTestParse
     * @param string $subscriptionFileName
     * @param $expected
     */
    public function testSubscriptionKeyAsciiForm_Parse(string $subscriptionFileName, $expected)
    {
        $key = $this->getDummySubscriptionKey($subscriptionFileName);

        if (is_string($expected)) {
            $this->expectException($expected);
        }

        $actual = $this->_licenseKeyForm->parse($key)->toArray();

        if (!is_string($expected)) {
            $this->assertSame($expected['customer_id'], $actual['customer_id']);
            $this->assertSame($expected['subscription_id'], $actual['subscription_id']);
            $this->assertSame($expected['users'], $actual['users']);
            $this->assertSame($expected['created'], $actual['created']);
            $this->assertSame($expected['expiry'], $actual['expiry']);
            $this->assertSame($expected['email'], $actual['email']);
            $this->assertSame(trim($key), $actual['data']);
        }
    }
}
