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
 * @since         2.7.0
 */
namespace Passbolt\TestData\Lib\SaveStrategy;

use Exception;
use Passbolt\TestData\Lib\DataCommand;

class SaveEntity
{
    /**
     * The shell the strategy is executing on.
     *
     * @var \Passbolt\TestData\Lib\DataCommand
     */
    private DataCommand $shell;

    /**
     * Constructor
     *
     * @param \Passbolt\TestData\Lib\DataCommand $shell The console the strategy is executing by.
     */
    public function __construct(DataCommand $shell)
    {
        $this->shell = $shell;
    }

    /**
     * Save data
     *
     * @param array $data The data to save
     * @return bool
     */
    public function save(array $data = []): bool
    {
        foreach ($data as $row) {
            try {
                $this->saveEntity($row);
            } catch (Exception $e) {
                $this->shell->io->err(sprintf('Data "%s" from "%s" could not be inserted', $row['id'], $this->shell->entityName)); //phpcs:ignore
                $this->shell->io->err(print_r($row, true));
                $this->shell->io->warning($e->getMessage());

                return false;
            }
        }

        return true;
    }

    /**
     * Insert an entity.
     *
     * @param array $data The entity data
     * @throws \Exception if the entity can not be validated or saved
     * @return void
     */
    public function saveEntity(array $data = []): void
    {
        $entity = $this->shell->Table->newEmptyEntity();
        $entity->setAccess('*', true);
        $entity->patch($data);

        $errors = $entity->getErrors();
        if ($errors) {
            $this->shell->io->out('Unable to validate the entity data');
            $this->shell->io->out(json_encode($errors));
            $this->shell->io->out(json_encode($data));
            throw new Exception('Unable to save the entity data');
        }

        if (!$this->shell->Table->save($entity, ['checkRules' => false, 'atomic' => false])) {
            $errors = $entity->getErrors();
            $this->shell->io->out('Unable to save the entity');
            $this->shell->io->out(json_encode($errors));
            $this->shell->io->out(json_encode($data));
            throw new Exception('Unable to save the entity data');
        }
    }
}
