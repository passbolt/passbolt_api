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
 * @since         3.2.0
 */

namespace Passbolt\Locale\Test\TestCase\Controller;

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use Cake\ORM\TableRegistry;
use Passbolt\Locale\Utility\LocaleUtility;

class AccountLocalesSelectControllerTest extends AppIntegrationTestCase
{
    /**
     * @var AccountSettingsTable
     */
    public $accountSettings;

    public function setUp(): void
    {
        parent::setUp();
        $this->accountSettings = TableRegistry::getTableLocator()->get('Passbolt/AccountSettings.AccountSettings');
    }

    public function tearDown(): void
    {
        parent::tearDown();
        LocaleUtility::clearOrganisationLocale();
    }

    public function testAccountLocalesSelectAsGuestFails()
    {
        $this->postJson('/account/settings/locales.json');
        $this->assertAuthenticationError();
    }

    public function testAccountLocalesSelectSuccess()
    {
        $user = UserFactory::make()->user()->persist();
        $this->logInAs($user);

        $value = 'en-US';
        $this->postJson('/account/settings/locales.json', compact('value'));
        $this->assertResponseSuccess();
        $this->assertSame(
            $value,
            $this->accountSettings->getByProperty($user->id, LocaleUtility::SETTING_PROPERTY)->get('value')
        );
    }

    public function testAccountLocalesSelectOnNonSupportedLocale()
    {
        $this->logInAsUser();

        $value = 'foo-BAR';
        $this->postJson('/account/settings/locales.json', compact('value'));
        $this->assertBadRequestError('The locale foo-BAR is not supported.');
    }
}
