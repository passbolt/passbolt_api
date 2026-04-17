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

namespace Passbolt\Subscription\Test\TestCase\Form;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\Utility\Hash;
use Exception;
use Passbolt\Subscription\Form\SubscriptionKeyAsciiForm;
use Passbolt\Subscription\Test\DummySubscriptionTrait;

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
        $this->loadPlugins(['Passbolt/Subscription' => []]);
        $this->_licenseKeyForm = new SubscriptionKeyAsciiForm();
        $this->setUpPathAndPublicSubscriptionKey();
    }

    public function testSubscriptionKeyAsciiForm_ParseSuccess()
    {
        $licenseStr = $this->getDummySubscriptionKey('subscription_dev');
        $this->_licenseKeyForm->setData(['key_ascii' => $licenseStr]);
        try {
            $licenseInfo = $this->_licenseKeyForm->parse();
        } catch (Exception $e) {
            $this->fail('The license does not validate: ' . $e->getMessage());
        }
        $expected = self::getValidSubscription();
        $actual = $licenseInfo->toArray();

        $this->assertSame($expected['customer_id'], $actual['customer_id']);
        $this->assertSame($expected['subscription_id'], $actual['subscription_id']);
        $this->assertSame($expected['users'], $actual['users']);
        $this->assertSame($expected['created'], $actual['created']);
        $this->assertSame($expected['expiry'], $actual['expiry']);
        $this->assertSame($expected['email'], $actual['email']);
        $this->assertSame(trim($licenseStr), $actual['data']);
    }

    public function testSubscriptionKeyAsciiForm_Parse_SuccessNewFormat()
    {
        Configure::write(
            'passbolt.plugins.subscription.subscriptionKey.public',
            PLUGINS . 'PassboltEe' . DS . 'Subscription' . DS . 'tests' . DS . 'Fixture' . DS . 'gpg' . DS . 'subscription_staging_public.key'
        );
        $licenseStr = $this->getDummySubscriptionKey('subscription_staging_timestamp');
        $this->_licenseKeyForm->setData(['key_ascii' => $licenseStr]);
        try {
            $licenseInfo = $this->_licenseKeyForm->parse();
        } catch (Exception $e) {
            $this->fail('The license does not validate: ' . $e->getMessage());
        }
        $actual = $licenseInfo->toArray();

        $this->assertSame('123', $actual['customer_id']);
        $this->assertSame('123', $actual['subscription_id']);
        $this->assertSame(10, $actual['users']);
        $this->assertSame('2025-04-22', $actual['created']);
        $this->assertSame('2027-04-22', $actual['expiry']);
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
    public static function dataForTestParse()
    {
        return [
            ['subscription_dev', self::getValidSubscription()],
            ['subscription_expired', self::getExpiredSubscription()],
            ['subscription_issuer_ada', Exception::class],
        ];
    }

    /**
     * @dataProvider dataForTestParse
     * @param string $subscriptionFileName
     * @param array|string $expected
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
