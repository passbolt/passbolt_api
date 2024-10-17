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
 * @since         4.10.0
 */
namespace Passbolt\Metadata\Service\Migration;

use Cake\Console\ConsoleIo;
use Cake\Http\Exception\BadRequestException;
use Cake\Utility\Inflector;

class MigrateAllV4ItemsToV5Service
{
    /**
     * Result of migration.
     *
     * @var array
     */
    private array $result = [
        'success' => true,
        'migrated' => [],
        'errors' => [],
    ];

    /**
     * Migrates all V4 items to V5.
     *
     * @param \Cake\Console\ConsoleIo $io Console IO object.
     * @return array
     */
    public function migrate(ConsoleIo $io): array
    {
        $services = MigrateAllV4ToV5ServiceCollector::get();

        foreach ($services as $service) {
            /** @var \Passbolt\Metadata\Service\Migration\V4ToV5MigrationServiceInterface $service */
            $service = new $service();

            $entityName = Inflector::pluralize($service->getHumanReadableName());

            $io->out(__('Migrating {0}...', $entityName));

            try {
                $result = $service->migrate();
            } catch (BadRequestException $e) {
                $this->addError(['error_message' => $e->getMessage(), 'entity' => $entityName]);
            }

            if (!empty($result['migrated'])) { // success
                $this->addMigrated($entityName, $result['migrated']);
            }
            if (!empty($result['errors'])) { // push errors
                $msg = __('Errors (encoded): {0}', json_encode($result['errors']));
                $this->addError(['error_message' => $msg, 'entity' => $entityName]);
            }

            $io->out(__('Migration process for {0} finished.', $entityName));
        }

        return $this->getResult();
    }

    /**
     * Returns migration result.
     *
     * @return array
     */
    public function getResult(): array
    {
        $this->result['success'] = empty($this->result['errors']);

        return $this->result;
    }

    /**
     * Append a new error to the result.
     *
     * @param array $error Error.
     * @return void
     */
    private function addError(array $error): void
    {
        $this->result['errors'][] = $error;
    }

    /**
     * @param string $entityName entity
     * @param array $migratedIds Migrated identifiers.
     * @return void
     */
    private function addMigrated(string $entityName, array $migratedIds): void
    {
        $this->result['migrated'][] = [
            'entity' => $entityName,
            'ids' => $migratedIds,
        ];
    }
}
