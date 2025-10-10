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

namespace Passbolt\Metadata\Service\Healthcheck\UserFriendly;

use App\Service\Healthcheck\HealthcheckCliInterface;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\HealthcheckServiceInterface;
use App\Service\Healthcheck\SkipHealthcheckInterface;
use App\Service\OpenPGP\OpenPGPCommonServerOperationsTrait;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query\SelectQuery;
use Passbolt\Metadata\Service\MetadataKeysSettingsGetService;

class ServerMissingAccessToMetadataKeyHealthcheck implements HealthcheckServiceInterface, HealthcheckCliInterface, SkipHealthcheckInterface // phpcs:ignore
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
     * @var bool
     */
    private bool $isSkipped = false;

    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        $metadataKeysSettingsDto = MetadataKeysSettingsGetService::getSettings();
        if ($metadataKeysSettingsDto->isKeyShareZeroKnowledge()) {
            $this->markAsSkipped();

            return $this;
        }

        $query = $this->fetchTable('Passbolt/Metadata.MetadataKeys')->find();
        $count = $query
            ->leftJoinWith('MetadataPrivateKeys', function (SelectQuery $q) {
                $expr = $q->newExpr()->isNull('MetadataPrivateKeys.user_id');

                return $q->where([$expr]);
            })
            ->where([
                $query->newExpr()->isNull('MetadataPrivateKeys.metadata_key_id'),
                $query->newExpr()->isNull('MetadataKeys.deleted'),
            ])
            ->count();

        if ($count === 0) {
            $this->status = true;
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
        return __('The server has access to the metadata keys or does not require access to it.');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return $this->errorMessage ?? __('The server does not have access to metadata key.');
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage(): array|string|null
    {
        return [
            __('When zero-knowledge mode is off, the server must have access to the metadata key.'),
            __('Without having access, the server won\'t be able to share the metadata private key with the users.'),
        ];
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
        return 'isServerHasAccessToMetadataKey';
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
