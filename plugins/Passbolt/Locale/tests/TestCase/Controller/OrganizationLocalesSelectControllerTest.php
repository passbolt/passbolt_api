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

use App\Test\Lib\AppIntegrationTestCase;
use Cake\ORM\TableRegistry;
use Passbolt\Locale\Utility\LocaleUtility;

class OrganizationLocalesSelectControllerTest extends AppIntegrationTestCase
{
    /**
     * @var OrganizationSettingsTable
     */
    public $organizationSettings;

    public function setUp(): void
    {
        parent::setUp();
        $this->organizationSettings = TableRegistry::getTableLocator()->get('OrganizationSettings');
    }

    public function tearDown(): void
    {
        parent::tearDown();
        LocaleUtility::clearOrganisationLocale();
    }

    public function testOrganizationLocalesSelectAsGuestFails()
    {
        $this->logInAsUser();
        $this->postJson('/locale/settings.json');
        $this->assertForbiddenError();
    }

    public function testOrganizationLocalesSelectSuccess()
    {
        $this->logInAsAdmin();
        $value = 'en-US';
        $this->postJson('/locale/settings.json', compact('value'));
        $this->assertResponseSuccess();
        $this->assertSame(
            $value,
            $this->organizationSettings->getByProperty(LocaleUtility::SETTING_PROPERTY)->get('value')
        );
    }

    public function testOrganizationLocalesSelectOnNonSupportedLocal()
    {
        $this->logInAsAdmin();
        $value = 'foo-BAR';
        $this->postJson('/locale/settings.json', compact('value'));
        $this->assertBadRequestError('The locale foo-BAR is not supported.');
    }
}
