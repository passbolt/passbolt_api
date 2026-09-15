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
 * @since         5.14.0
 */

namespace App\Service\Healthcheck\Database;

use App\Service\Cache\ModelCacheCheckService;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\Healthcheck\HealthcheckServiceInterface;
use Cake\Core\Exception\CakeException;

class ModelCacheDatabaseHealthcheck extends AbstractDatabaseHealthcheck
{
    protected const COMMAND = 'cake cache clear _cake_model_';
    protected const URL_HELP = 'https://www.passbolt.com/docs/hosting/useful-commands/';

    /**
     * Holds the mismatching table names with the list of human-readable differences.
     *
     * @var array<string, array<string>>
     */
    private array $mismatches = [];

    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        try {
            $this->mismatches = (new ModelCacheCheckService())->getCacheMismatches($this->getDatasource());
        } catch (CakeException) {
            // This check only warns on an actual cache-vs-database discrepancy. If the comparison cannot run at all
            // (database unreachable, cache not configured, ...), the underlying issue must be reported by other checks.
            $this->status = true;

            return $this;
        }

        $this->status = $this->mismatches === [];

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSuccessMessage(): string
    {
        return __('The model schema cache shows no discrepancies with the database schema.');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        $msg = __('The model schema cache shows discrepancies with the database schema.');

        foreach ($this->mismatches as $tableName => $diffs) {
            $msg .= ' ' . __('{0}: {1}.', $tableName, implode(', ', $diffs));
        }

        return $msg;
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage(): array|string|null
    {
        return [
            __('Clear the model schema cache so it is rebuilt from the database.'),
            __('Consider running `{0}`. More information: {1}', static::COMMAND, static::URL_HELP),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getLegacyArrayKey(): string
    {
        return 'modelCache';
    }

    /**
     * @inheritDoc
     */
    public function level(): string
    {
        // A stale model cache does not break the application on its own, so report it as a warning rather than an
        // error: it can be safely rebuilt by clearing the _cake_model_ cache (see the help message).
        return HealthcheckServiceCollector::LEVEL_WARNING;
    }

    /**
     * @inheritDoc
     */
    public function getKey(): string
    {
        return 'model-cache-database';
    }
}
