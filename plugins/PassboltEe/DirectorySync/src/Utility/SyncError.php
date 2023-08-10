<?php
declare(strict_types=1);

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
 * @since         2.2.0
 */
namespace Passbolt\DirectorySync\Utility;

use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\Entity;

/**
 * Directory factory class
 *
 * @package App\Utility
 */
class SyncError
{
    /**
     * @var \Cake\ORM\Entity|null
     */
    protected $entity;

    /**
     * @var \Exception|null
     */
    protected $exception;

    /**
     * SyncError constructor.
     *
     * @param \Cake\ORM\Entity|null $entity entity
     * @param \Exception|null $exception exception
     */
    public function __construct(?Entity $entity = null, ?\Exception $exception = null)
    {
        if (!isset($entity) && !isset($exception)) {
            throw new InternalErrorException('This is not a valid SyncError, no data provided');
        }
        $this->entity = $entity;
        $this->exception = $exception;
    }

    /**
     * Get entity
     *
     * @return \Cake\ORM\Entity|null
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Get Exception.
     *
     * @return \Exception|null
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * Serialize.
     *
     * @return array
     */
    public function __serialize(): array // phpcs:ignore
    {
        return [
            'entity' => serialize($this->entity),
            'version' => '2',
        ];
    }

    /**
     * Unserialize.
     *
     * @param array $data data
     * @return void
     */
    public function __unserialize(array $data): void // phpcs:ignore
    {
        foreach ($data as $key => $value) {
            if (in_array($key, ['$entity'])) {
                $this->{$key} = $value;
            }
        }
    }
}
