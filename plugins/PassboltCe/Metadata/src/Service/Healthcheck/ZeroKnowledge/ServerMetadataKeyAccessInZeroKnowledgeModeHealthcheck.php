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
 * @since         5.7.0
 */

namespace Passbolt\Metadata\Service\Healthcheck\ZeroKnowledge;

use App\Service\Healthcheck\HealthcheckCliInterface;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\HealthcheckServiceInterface;
use App\Service\Healthcheck\SkipHealthcheckInterface;
use App\Service\OpenPGP\OpenPGPCommonServerOperationsTrait;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Metadata\Service\MetadataKeysSettingsGetService;
use Passbolt\Metadata\Service\MetadataTypesSettingsGetService;

class ServerMetadataKeyAccessInZeroKnowledgeModeHealthcheck implements HealthcheckServiceInterface, HealthcheckCliInterface, SkipHealthcheckInterface // phpcs:ignore
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
     * @var bool
     */
    private bool $isSkipped = false;

    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        $metadataTypesSettingsDto = MetadataTypesSettingsGetService::getSettings();
        if (!$metadataTypesSettingsDto->isV5Enabled()) {
            $this->markAsSkipped();

            return $this;
        }

        $metadataKeysSettingsDto = MetadataKeysSettingsGetService::getSettings();
        if (!$metadataKeysSettingsDto->isKeyShareZeroKnowledge()) {
            $this->markAsSkipped();

            return $this;
        }

        $query = $this->fetchTable('Passbolt/Metadata.MetadataPrivateKeys')->find();
        $count = $query
            ->where([$query->newExpr()->isNull('user_id')])
            ->count();

        if ($count === 0) {
            $this->status = true;

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
        return __('The server does not have access to the server metadata private key in Zero-knowledge mode.'); // phpcs:ignore
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __('The server has access to the server metadata private key while in Zero-knowledge mode.');
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage(): array|string|null
    {
        return __('When Zero-knowledge mode is enabled, the server should not have access to the server metadata private key.'); // phpcs:ignore
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
        return 'isServerMetadataKeyAccessInZeroKnowledgeMode';
    }

    /**
     * @inheritDoc
     */
    public function markAsSkipped(): void
    {
        $this->isSkipped = true;
    }

    /**
     * @inheritDoc
     */
    public function isSkipped(): bool
    {
        return $this->isSkipped;
    }
}
