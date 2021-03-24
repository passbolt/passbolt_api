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

namespace Passbolt\Locale\Test\TestCase\Middleware;

use App\Test\Factory\OrganizationSettingFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use Cake\I18n\I18n;
use Passbolt\Locale\Service\GetRequestLocaleService;

class LocaleMiddlewareTest extends AppIntegrationTestCase
{
    public function tearDown(): void
    {
        parent::tearDown();
        I18n::setLocale(I18n::DEFAULT_LOCALE);
    }

    public function testLocaleMiddlewareUnauthenticatedRequestWithOrgaSetting()
    {
        $locale = 'foo';
        OrganizationSettingFactory::make()->locale($locale)->persist();

        $this->getJson('/auth/is-authenticated.json');

        $this->assertSame(I18n::DEFAULT_LOCALE, I18n::getLocale());
    }

    public function testLocaleMiddlewareUnauthenticatedRequestWithQuerySetting(): void
    {
        $locale = 'fr-FR';
        $localeKey = GetRequestLocaleService::QUERY_KEY;
        OrganizationSettingFactory::make()->locale('bar')->persist();

        $this->getJson('/auth/is-authenticated.json?' . $localeKey . '=' . $locale);

        $this->assertSame('fr_FR', I18n::getLocale());
    }

    public function testLocaleMiddlewareAuthenticatedWithAccountSetting(): void
    {
        $locale = 'fr-FR';
        OrganizationSettingFactory::make()->locale('bar')->persist();

        $user = UserFactory::make()->user()->withLocale($locale)->persist();

        $this->logInAs($user);
        $this->getJson('/auth/is-authenticated.json');
        $this->assertSame('fr_FR', I18n::getLocale());
    }

    public function testLocaleMiddlewareAuthenticatedWithAccountSettingAndQuerySettings(): void
    {
        $locale = 'fr-FR';
        $localeKey = GetRequestLocaleService::QUERY_KEY;
        OrganizationSettingFactory::make()->locale('bar')->persist();
        $user = UserFactory::make()->user()->withLocale('foo')->persist();

        $this->logInAs($user);
        $this->getJson('/auth/is-authenticated.json?' . $localeKey . '=' . $locale);
        $this->assertSame('fr_FR', I18n::getLocale());
    }
}
