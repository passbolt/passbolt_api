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
namespace App\Controller\Healthcheck;

use App\Controller\AppController;
use App\Service\Healthcheck\Application\LatestVersionApplicationHealthcheck;
use App\Service\Healthcheck\Application\SelfRegistrationProviderApplicationHealthcheck;
use App\Service\Healthcheck\Core\FullBaseUrlCoreHealthcheck;
use App\Service\Healthcheck\Database\ConnectDatabaseHealthcheck;
use App\Service\Healthcheck\Database\TablesCountDatabaseHealthcheck;
use App\Service\Healthcheck\Environment\PhpVersionHealthcheck;
use App\Service\Healthcheck\Gpg\FingerprintMatchGpgHealthcheck;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\Ssl\HostValidSslHealthcheck;
use App\Service\Healthcheck\Ssl\IsRequestHttpsSslHealthcheck;
use App\Service\Healthcheck\Ssl\PeerValidSslHealthcheck;
use Cake\Collection\Collection;
use Cake\Collection\CollectionInterface;
use Cake\Core\Configure;
use Cake\Event\EventInterface;
use Cake\Http\Exception\ForbiddenException;
use Cake\Utility\Hash;
use Passbolt\SmtpSettings\Service\Healthcheck\SettingsValidationSmtpSettingsHealthcheck;
use Passbolt\SmtpSettings\Service\Healthcheck\SmtpSettingsSettingsSourceHealthcheck;

class HealthcheckIndexController extends AppController
{
    public const PASSBOLT_PLUGINS_HEALTHCHECK_SECURITY_INDEX_ENDPOINT_ENABLED =
        'passbolt.plugins.healthcheck.security.indexEndpointEnabled';

    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        $this->throwErrorIsEndpointIsDisabled();

        $this->Authentication->allowUnauthenticated(['index']);

        return parent::beforeFilter($event);
    }

    /**
     * Index
     * Display information about the passbolt instance
     * It is only available in debug mode and for logged in administrators
     *
     * @param \App\Service\Healthcheck\HealthcheckServiceCollector $healthcheckServiceCollector Health check service collector.
     * @param \App\Service\Healthcheck\Ssl\IsRequestHttpsSslHealthcheck $isRequestHttpsSslHealthcheck SSL enabled health check.
     * @return void
     * @throws \Cake\Http\Exception\ForbiddenException if the requesting user is not an admin
     */
    public function index(
        HealthcheckServiceCollector $healthcheckServiceCollector,
        IsRequestHttpsSslHealthcheck $isRequestHttpsSslHealthcheck
    ) {
        $this->User->assertIsAdmin();

        $ignoreDomains = $this->getDomainsIgnore();
        $healthcheckServices = [];
        foreach ($healthcheckServiceCollector->getServices() as $healthcheckService) {
            if (in_array($healthcheckService->domain(), $ignoreDomains)) {
                continue;
            }

            $healthcheckServices[] = $healthcheckService;
        }
        $healthcheckServices[] = $isRequestHttpsSslHealthcheck;

        $resultCollection = new Collection([]);
        foreach ($healthcheckServices as $healthcheckService) {
            $result = $healthcheckService->check();

            $resultCollection = $resultCollection->appendItem($result);
        }

        $resultsGroupByDomain = $resultCollection->groupBy(function ($result) {
            return $result->domain();
        });

        if (!$this->request->is('json')) {
            $body = [];
            foreach ($resultsGroupByDomain as $domain => $checkResults) {
                $key = $healthcheckServiceCollector->getTitleFromDomain($domain);
                $body[$key] = $checkResults;
            }
            $this->viewBuilder()
                ->setLayout('login')
                ->setTemplatePath('Healthcheck')
                ->setTemplate('index');
            $this->success(__('All checks ran successfully!'), $body);
        } else {
            $healthcheckResult = $this->formatCollectionResponseAsPerLegacy($resultsGroupByDomain);

            $this->success(__('The operation was successful.'), $healthcheckResult);
        }
    }

    /**
     * @return void
     * @throws \Cake\Http\Exception\ForbiddenException if the endpoint is deactivated
     */
    private function throwErrorIsEndpointIsDisabled(): void
    {
        if (!Configure::read(self::PASSBOLT_PLUGINS_HEALTHCHECK_SECURITY_INDEX_ENDPOINT_ENABLED)) {
            throw new ForbiddenException(__('Healthcheck security index endpoint disabled.'));
        }
    }

    /**
     * @return array
     */
    private function getDomainsIgnore(): array
    {
        return [HealthcheckServiceCollector::DOMAIN_JWT];
    }

    /**
     * Formats given collection as per legacy array structure. This helps us keep backward compatibility.
     *
     * @deprecated As of v4.7.0, this is just to keep backward compatibility.
     * @param \Cake\Collection\CollectionInterface $resultsGroupByDomain Result collection to format as per legacy array format.
     * @return array
     */
    private function formatCollectionResponseAsPerLegacy(CollectionInterface $resultsGroupByDomain): array
    {
        $result = [];

        /** @var \App\Service\Healthcheck\HealthcheckServiceInterface[] $checkResults */
        foreach ($resultsGroupByDomain as $domainKey => $checkResults) {
            if ($domainKey === HealthcheckServiceCollector::DOMAIN_CONFIG_FILES) {
                $domainKey = 'configFile';
            }
            $result[$domainKey] = [];
            foreach ($checkResults as $checkResult) {
                $value = $checkResult->isPassed();

                /**
                 * **Note:**
                 * These are conditions that is required to be backward compatible.
                 * This is @deprecated way (appending keys into result array) and should not be done anywhere else.
                 */
                if ($checkResult instanceof LatestVersionApplicationHealthcheck) {
                    // Application domain additional fields
                    if ($checkResult->isExceptionThrown()) {
                        $value = null;
                    }

                    $result[$domainKey]['configPath'] = CONFIG . 'passbolt.php';

                    $result[$domainKey]['info']['remoteVersion'] = $checkResult->getRemoteVersion();
                    $result[$domainKey]['info']['currentVersion'] = Configure::read('passbolt.version');
                } elseif ($checkResult instanceof FullBaseUrlCoreHealthcheck) {
                    // Core domain additional fields
                    $result[$domainKey]['info']['fullBaseUrl'] = Configure::read('App.fullBaseUrl');
                } elseif ($checkResult instanceof ConnectDatabaseHealthcheck) {
                    // Database domain additional fields
                    $result[$domainKey]['supportedBackend'] = $value;
                } elseif ($checkResult instanceof TablesCountDatabaseHealthcheck) {
                    // Database domain info fields
                    $result[$domainKey]['info']['tablesCount'] = $checkResult->getTableCount();
                } elseif ($checkResult instanceof PhpVersionHealthcheck) {
                    // Environment domain additional fields
                    $result[$domainKey]['gnupg'] = extension_loaded('gnupg');
                    $result[$domainKey]['info']['phpVersion'] = PHP_VERSION;
                } elseif ($checkResult instanceof SmtpSettingsSettingsSourceHealthcheck) {
                    // SMTP settings additional fields
                    $result[$domainKey]['source'] = $checkResult->getSource();
                } elseif ($checkResult instanceof SettingsValidationSmtpSettingsHealthcheck) {
                    $value = false;
                    if ($checkResult->getValidationError() !== '') {
                        $value = $checkResult->getValidationError();
                    }
                } elseif ($checkResult instanceof SelfRegistrationProviderApplicationHealthcheck) {
                    $value = $checkResult->getProvider();
                } elseif ($checkResult instanceof FingerprintMatchGpgHealthcheck) {
                    // GPG additional fields
                    $result[$domainKey]['gpgKeyPublicReadable'] = $checkResult->gpgKeyPublicReadable();
                    $result[$domainKey]['gpgKeyPrivateReadable'] = $checkResult->gpgKeyPrivateReadable();
                    $result[$domainKey]['gpgKeyPrivateFingerprint'] = $checkResult->isPrivateKeyInfoOK();
                    $result[$domainKey]['gpgKeyPublic'] = (Configure::read('passbolt.gpg.serverKey.public') !== null);
                    $result[$domainKey]['gpgKeyPrivate'] = (Configure::read('passbolt.gpg.serverKey.private') !== null);
                    $result[$domainKey]['gpgKey'] = (Configure::read('passbolt.gpg.serverKey.fingerprint') !== null);
                    $result[$domainKey]['info']['gpgKeyPrivate'] = Configure::read('passbolt.gpg.serverKey.private');
                    $result[$domainKey]['info']['gpgHome'] = $checkResult->gpgHome();
                } elseif ($checkResult instanceof IsRequestHttpsSslHealthcheck) {
                    // We don't want to set this in JSON response
                    continue;
                } elseif ($checkResult instanceof HostValidSslHealthcheck || $checkResult instanceof PeerValidSslHealthcheck) { // phpcs:ignore
                    if (!empty($checkResult->getHelpMessage())) {
                        $result[$domainKey]['info'] = $checkResult->getHelpMessage()[0];
                    }
                }

                $result[$domainKey] = Hash::insert($result[$domainKey], $checkResult->getLegacyArrayKey(), $value);
            }
        }

        return $result;
    }
}
