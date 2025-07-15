<?php
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
 * @since         2.7.0
 */
namespace PassboltTestData\Lib\SaveStrategy;

use Cake\Core\Configure;
use PassboltTestData\Lib\DataCommand;

class SaveMany
{
    /**
     * The shell the strategy is executing on.
     *
     * @var DataCommand
     */
    private $shell;

    /**
     * Constructor
     *
     * @param DataCommand $shell The console the strategy is executing by.
     */
    public function __construct(DataCommand $shell)
    {
        $this->shell = $shell;
    }

    /**
     * Save resources using saveMany.
     *
     * @param array $data The data to save
     * @return void
     */
    public function save(array $data): void
    {
        $chunkSize = Configure::read('PassboltTestData.scenarios.large.install.count.chunk_size');
        $chunks = array_chunk($data, $chunkSize);
        $total = count($chunks);

        $this->shell->io->out("Inserting \"" . $this->shell->entityName);
        $progress = $this->shell->displayProgressBar($total);
        foreach ($chunks as $i => $chunk) {
            $this->saveEntities($chunk);
            if (!is_null($progress)) {
                $progress->increment(1);
                $progress->draw();
                if ($i + 1 == $total) {
                    $this->shell->io->out("\n");
                }
            }
        }
    }

    /**
     * Save a chunk of entities
     *
     * @param array $data The data to save
     * @return void
     */
    public function saveEntities(array $data): void
    {
        $entities = $this->shell->Table->newEntities($data, ['accessibleFields' => ['*' => true], 'validate' => false]);
        foreach ($entities as $entity) {
            $entity->setAccess('*', true);
        }
        $this->shell->Table->saveMany($entities, ['checkRules' => false, 'atomic' => false]);
    }
}
