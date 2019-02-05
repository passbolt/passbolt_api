<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.7.0
 */
namespace PassboltTestData\Lib\SaveStrategy;

use Cake\Console\Shell;
use Cake\Core\Configure;

class SaveMany
{
    /**
     * The shell the strategy is executing on.
     *
     * @var Shell
     */
    private $shell;

    /**
     * Constructor
     *
     * @param Shell $shell The console the strategy is executing by.
     */
    public function __construct(Shell $shell)
    {
        $this->shell = $shell;
    }

    /**
     * Save resources using saveMany.
     *
     * @param array $data The data to save
     * @return void
     */
    public function save($data)
    {
        $chunkSize = Configure::read('PassboltTestData.scenarios.large.install.count.chunk_size');
        $chunks = array_chunk($data, $chunkSize);
        $total = count($chunks);

        $this->shell->out("Inserting \"" . $this->shell->entityName);
        $progress = $this->shell->displayProgressBar($total);
        foreach ($chunks as $i => $chunk) {
            $result = $this->saveEntities($chunk);
            if (!is_null($progress)) {
                $progress->increment(1);
                $progress->draw();
                if ($i + 1 == $total) {
                    $this->shell->out("\n");
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
    public function saveEntities(array $data)
    {
        $entities = $this->shell->_Entity->newEntities($data, ['accessibleFields' => ['*' => true], 'validate' => false]);
        foreach ($entities as $entity) {
            $entity->setAccess('*', true);
        }
        $this->shell->_Entity->saveMany($entities, ['checkRules' => false, 'atomic' => false]);
    }
}
