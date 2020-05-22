<?php
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

namespace Passbolt\License\Test\TestCase\Form;

use App\Utility\UuidFactory;
use Cake\Chronos\Date;
use Cake\TestSuite\TestCase;
use Cake\Utility\Hash;
use Passbolt\License\Form\LicenseKeyDataForm;

class LicenseKeyDataFormTest extends TestCase
{
    protected $baseTestPath;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Groups', 'app.Base/Favorites',
        'app.Base/Profiles', 'app.Base/Gpgkeys', 'app.Base/Resources',
        'app.Alt0/GroupsUsers', 'app.Alt0/Permissions',
        'plugin.Passbolt/Tags.Base/Tags', 'plugin.Passbolt/Tags.Alt0/ResourcesTags',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->loadPlugins(['Passbolt/License']);
        $this->baseTestPath = PLUGINS . 'Passbolt' . DS . 'License' . DS . 'tests';
    }

    public function testKeyDataValidate_ErrorCreatedInFuture()
    {
        $data = [
            'customer_id' => UuidFactory::uuid(),
            'plan_id' => UuidFactory::uuid(),
            'users' => 10000,
            'created' => (new Date())->addDays(2)->toIso8601String(),
            'expiry' => (new Date())->addyear()->toIso8601String(),
        ];
        $form = new LicenseKeyDataForm();
        $isValid = $form->execute($data);
        $errors = $form->getErrors();

        $this->assertFalse($isValid);
        $this->assertCount(1, $errors);
        $this->assertNotEmpty(Hash::get($errors, 'created.is_created_in_past'));
    }

    public function testKeyDataValidate_ErrorExpired()
    {
        $data = [
            'customer_id' => UuidFactory::uuid(),
            'plan_id' => UuidFactory::uuid(),
            'users' => 10000,
            'created' => (new Date())->subMonths(6)->toIso8601String(),
            'expiry' => (new Date())->subMonth()->toIso8601String(),
        ];
        $form = new LicenseKeyDataForm();
        $isValid = $form->execute($data);
        $errors = $form->getErrors();

        $this->assertFalse($isValid);
        $this->assertCount(1, $errors);
        $this->assertNotEmpty(Hash::get($errors, 'expiry.is_not_expired'));
    }

    public function testKeyDataValidate_ErrorUsersLimitReached()
    {
        $data = [
            'customer_id' => UuidFactory::uuid(),
            'plan_id' => UuidFactory::uuid(),
            'users' => 1,
            'created' => (new Date())->subMonths(6)->toIso8601String(),
            'expiry' => (new Date())->addMonths(6)->toIso8601String(),
        ];
        $form = new LicenseKeyDataForm();
        $isValid = $form->execute($data);
        $errors = $form->getErrors();

        $this->assertFalse($isValid);
        $this->assertCount(1, $errors);
        $this->assertNotEmpty(Hash::get($errors, 'users.is_within_range'));
    }
}
