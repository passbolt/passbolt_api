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
 * @since         5.13.0
 */
namespace Passbolt\Edition\Service\Healthcheck\Application;

use App\Model\Entity\OrganizationSetting;
use App\Service\Healthcheck\HealthcheckCliInterface;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\HealthcheckServiceInterface;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Edition\Model\Dto\EditionDto;

/**
 * Reports which edition the instance is currently serving by reading the
 * `edition` row from `organization_settings`. Warns when the row is missing —
 * `EditionGetService` silently falls back to CE in that case, so this
 * healthcheck is the only signal that the runtime edition is a default and
 * not an explicit setting.
 */
class EditionPresentInDatabaseApplicationHealthcheck implements HealthcheckServiceInterface, HealthcheckCliInterface
{
    use LocatorAwareTrait;

    private bool $status = false;

    private ?EditionDto $edition = null;

    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        /** @var \App\Model\Entity\OrganizationSetting|null $row */
        $row = $this->fetchTable('Passbolt/Edition.EditionOrganization')->find()->first();
        if ($row instanceof OrganizationSetting) {
            $this->edition = EditionDto::fromOrgSettingRow($row);
            $this->status = true;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function domain(): string
    {
        return HealthcheckServiceCollector::DOMAIN_APPLICATION;
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
        // Missing edition row should throw an error as it will result in an inconsistent state of the DB
        return HealthcheckServiceCollector::LEVEL_ERROR;
    }

    /**
     * @inheritDoc
     */
    public function getSuccessMessage(): string
    {
        $edition = $this->edition?->getEdition() ?? '';

        return __('The edition served is {0}.', strtoupper($edition));
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __('Edition row is missing from organization_settings; defaulting to CE.');
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage(): array|string|null
    {
        return [
            __('No edition is recorded in organization_settings — the instance is falling back to CE by default.'),
            __('Run "bin/cake passbolt populate_edition" or upload a subscription key from the admin UI.'),
        ];
    }

    /**
     * @inheritDoc
     */
    public function cliOption(): string
    {
        return HealthcheckServiceCollector::DOMAIN_APPLICATION;
    }

    /**
     * @inheritDoc
     */
    public function getLegacyArrayKey(): string
    {
        return 'editionFlag';
    }

    /**
     * @inheritDoc
     */
    public function getKey(): string
    {
        return 'edition-present-in-database';
    }
}
