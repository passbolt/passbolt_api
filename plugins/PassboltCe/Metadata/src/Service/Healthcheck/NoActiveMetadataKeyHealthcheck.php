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
 * @since         5.4.0
 */

namespace Passbolt\Metadata\Service\Healthcheck;

use App\Service\Healthcheck\HealthcheckCliInterface;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\HealthcheckServiceInterface;
use App\Service\OpenPGP\OpenPGPCommonServerOperationsTrait;
use Cake\Core\Configure;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query\SelectQuery;
use Passbolt\Metadata\Service\MetadataTypesSettingsGetService;
use PDOException;

/**
 * No active metadata key when encrypted metadata is enabled
 */
class NoActiveMetadataKeyHealthcheck implements HealthcheckServiceInterface, HealthcheckCliInterface
{
    use LocatorAwareTrait;
    use OpenPGPCommonServerOperationsTrait;

    /**
     * Status of this health check if it is passed or failed.
     *
     * @var bool
     */
    private bool $status = false;

    /**
     * @var string|null
     */
    private ?string $errorMessage = null;

    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        $settingsDto = MetadataTypesSettingsGetService::getSettings();
        if (!$settingsDto->isV5Enabled()) {
            $this->status = true;

            return $this;
        }

        try {
            $this->fetchTable('Passbolt/Metadata.MetadataKeys')
                ->find('active')
                ->innerJoinWith('MetadataPrivateKeys', function (SelectQuery $q) {
                    $expr = $q->newExpr()->isNull('MetadataPrivateKeys.user_id');

                    return $q->where([$expr]);
                })
                ->orderBy(['MetadataKeys.created' => 'DESC'])
                ->firstOrFail();

            $this->status = true;
        } catch (PDOException | RecordNotFoundException $exception) {
            $this->errorMessage = __('No active metadata key found.');
            if (Configure::read('debug')) {
                $this->errorMessage .= ' ' . $exception->getMessage();
            }

            // No metadata private key found
            return $this;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function domain(): string
    {
        return HealthcheckServiceCollector::DOMAIN_METADATA;
    }

    /**
     * @inheritDoc
     */
    public function isPassed(): bool
    {
        return $this->status;
    }

    /**
     * @inheritDoc
     */
    public function level(): string
    {
        return HealthcheckServiceCollector::LEVEL_ERROR;
    }

    /**
     * @inheritDoc
     */
    public function getSuccessMessage(): string
    {
        return __('Active metadata key found or not required.');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        $this->errorMessage = $this->errorMessage ?? __('No active metadata key found.');

        return $this->errorMessage;
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage(): array|string|null
    {
        return null;
    }

    /**
     * CLI Option for this check.
     *
     * @return string
     */
    public function cliOption(): string
    {
        return HealthcheckServiceCollector::DOMAIN_METADATA;
    }

    /**
     * @inheritDoc
     */
    public function getLegacyArrayKey(): string
    {
        return 'noActiveMetadataKey';
    }
}
