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
 * @since         5.11.0
 */

namespace Passbolt\Scim\Test\TestCase\Identifier\Resolver;

use App\Service\OpenPGP\OpenPGPCommonServerOperationsTrait;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\I18n\Date;
use Cake\Log\Log;
use Passbolt\Scim\Test\Utility\ScimApiIntegrationTestCase;

class ScimResolverExpiredTokenTest extends ScimApiIntegrationTestCase
{
    use OpenPGPCommonServerOperationsTrait;

    public function testScimResolver_ExpiredToken_LogsWarning(): void
    {
        // Replace the default settings with an expired date
        $this->setExpiredDateOnSettings(Date::now()->modify('-1 day')->format('Y-m-d'));

        // Set up a log engine to capture warnings
        Log::setConfig('scim_test_warning', [
            'className' => 'Array',
            'levels' => ['warning'],
        ]);

        $this->configScimAuth();
        $this->get($this->getScimEndpoint('ServiceProviderConfig'));
        $this->assertResponseCode(200);

        /** @var \Cake\Log\Engine\ArrayLog $logger */
        $logger = Log::engine('scim_test_warning');
        $logs = $logger->read();
        Log::drop('scim_test_warning');

        $found = false;
        foreach ($logs as $log) {
            if (str_contains($log, 'The SCIM secret token is expired, you are requested to rotate it.')) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found, 'Expected warning log about expired SCIM token was not found.');
    }

    public function testScimResolver_NonExpiredToken_DoesNotLogWarning(): void
    {
        // Default factory creates settings with a future expired date — no need to override

        Log::setConfig('scim_test_warning', [
            'className' => 'Array',
            'levels' => ['warning'],
        ]);

        $this->configScimAuth();
        $this->get($this->getScimEndpoint('ServiceProviderConfig'));
        $this->assertResponseCode(200);

        /** @var \Cake\Log\Engine\ArrayLog $logger */
        $logger = Log::engine('scim_test_warning');
        $logs = $logger->read();
        Log::drop('scim_test_warning');

        foreach ($logs as $log) {
            $this->assertStringNotContainsString(
                'The SCIM secret token is expired',
                $log,
                'Unexpected warning log about expired SCIM token was found.'
            );
        }
    }

    private function setExpiredDateOnSettings(string $expiredDate): void
    {
        /** @var \Passbolt\Scim\Model\Table\ScimSettingsTable $table */
        $table = $this->fetchTable('Passbolt/Scim.ScimSettings');
        /** @var \Passbolt\Scim\Model\Entity\ScimSetting $settings */
        $settings = $table->find()->firstOrFail();

        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setDecryptKeyWithServerKey($gpg);
        $data = json_decode($gpg->decrypt($settings->value), associative: true);
        $data['expired'] = $expiredDate;

        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setEncryptKeyWithServerKey($gpg);
        $settings->set('value', $gpg->encrypt(json_encode($data)));
        $table->saveOrFail($settings);
    }
}
