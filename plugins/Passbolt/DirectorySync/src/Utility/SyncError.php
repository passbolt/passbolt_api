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
 * @since         2.2.0
 */
namespace Passbolt\DirectorySync\Utility;

use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\Entity;

/**
 * Directory factory class
 * @package App\Utility
 */
class SyncError implements \Serializable
{
    protected $entity;
    protected $data;
    protected $exception;

    /**
     * SyncError constructor.
     *
     * @param Entity|null $entity entity
     * @param \Exception|null $exception exception
     */
    public function __construct(Entity $entity = null, \Exception $exception = null)
    {
        if (!isset($data) && !isset($entity) && !isset($exception)) {
            throw new InternalErrorException(__('This is not a valid SyncError, no data provided'));
        }
        $this->entity = $entity;
        $this->exception = $exception;
    }

    /**
     * Get entity
     * @return Entity|null
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Get Exception.
     * @return \Exception|null
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * Get data.
     * @return mixed
     */
    public function getData()
    {
        return $this->getData();
    }

    /**
     * Serialize.
     * @return string
     */
    public function serialize()
    {
        return serialize([
            'entity' => serialize($this->entity),
            // TODO add exception serialization
            'version' => '2'
        ]);
    }

    /**
     * Unserialize.
     * @param string $serialized serialized
     * @return void
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        foreach ($data as $key => $value) {
            if (in_array($key, ['$entity'])) {
                $this->{$key} = $value;
            }
        }
    }
}
