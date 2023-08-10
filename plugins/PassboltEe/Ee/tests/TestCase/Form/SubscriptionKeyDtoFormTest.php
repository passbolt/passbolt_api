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

use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\Chronos\Date;
use Cake\TestSuite\TestCase;
use Cake\Utility\Hash;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\Ee\Form\SubscriptionKeyDtoForm;

class SubscriptionKeyDtoFormTest extends TestCase
{
    use TruncateDirtyTables;

    protected $baseTestPath;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->loadPlugins(['Passbolt/Ee' => []]);
        $this->baseTestPath = PLUGINS . 'PassboltEe' . DS . 'Ee' . DS . 'tests';
    }

    private function mergeWithStandardData(array $data)
    {
        return array_merge([
            'customer_id' => UuidFactory::uuid(),
            'subscription_id' => UuidFactory::uuid(),
            'email' => 'foo@passbolt.test',
            'users' => 2,
            'created' => Date::yesterday()->toAtomString(),
            'expiry' => Date::tomorrow()->toAtomString(),
        ], $data);
    }

    public function dataForSubscriptionKeyValidator()
    {
        $emoji = "\u{1F30F}";

        return [
            [[], []],
            [['customer_id' => null], ['customer_id']],
            [['customer_id' => $emoji], ['customer_id']],
            [['customer_id' => '   '], []],
            [['customer_id' => 'pb_comlyobasrem'], []],
            [['customer_id' => 'AzyWC2S9trS4K3cox'], []],
            [['customer_id' => 'sub_FuxnS83EKMWxoP'], []],
            [['customer_id' => 'email@passbolt.com'], []],
            [['customer_id' => 'free trial'], []],
            [['subscription_id' => null], ['subscription_id']],
            [['subscription_id' => $emoji], ['subscription_id']],
            [['email' => null], ['email']],
            [['email' => 'blah'], ['email']],
            [['users' => null], ['users']],
            [['users' => 0], ['users']],
            [['created' => null], ['created']],
            [['created' => Date::tomorrow()->toAtomString()], ['created']],
            [['expiry' => null], ['expiry']],
            [['expiry' => Date::yesterday()->toAtomString()], ['expiry']],
        ];
    }

    /**
     * Run validation on all kinds of possible errors.
     *
     * @dataProvider dataForSubscriptionKeyValidator
     * @param $data
     * @param $errorMessages
     * @param int $nUsers
     * @throws \Exception
     */
    public function testSubscriptionKeyValidator($data, $errorMessages)
    {
        UserFactory::make()->user()->persist();

        $form = new SubscriptionKeyDtoForm();
        $isValid = $form->execute($this->mergeWithStandardData($data));
        $errors = $form->getErrors();

        $this->assertCount(count($errorMessages), $errors);
        $this->assertSame(count($errorMessages) === 0, $isValid);
        foreach ($errorMessages as $errorMessage) {
            $this->assertNotEmpty(Hash::get($errors, $errorMessage));
        }
    }
}
