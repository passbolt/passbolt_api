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
 * @since         2.14.0
 */

namespace App\Controller\Events;

use App\Model\Entity\Resource;
use App\Utility\UserAccessControl;
use Cake\Event\Event;
use Exception;
use InvalidArgumentException;

class ResourceCreateEvent extends Event
{
    const EVENT_NAME = 'resources.resource.create';

    /** @var array */
    private $payload;

    /** @var Resource */
    private $resource;

    /** @var UserAccessControl */
    private $uac;

    /**
     * @param string $name Event name
     * @param null $subject Subject
     * @param array|null $data Data
     */
    public function __construct($name, $subject = null, array $data = null)
    {
        $this->resource = $data['resource'];
        $this->payload = $data['payload'];
        $this->uac = $data['uac'];

        if (!$this->resource instanceof Resource) {
            throw new InvalidArgumentException('`resource` must be an instance of ' . Resource::class);
        }

        if (!is_array($this->payload)) {
            throw new InvalidArgumentException('`payload` data must be an array.');
        }

        if (!$this->uac instanceof UserAccessControl) {
            throw new InvalidArgumentException('`uac` must be an instance of ' . UserAccessControl::class);
        }

        parent::__construct($name, $subject, $data);
    }

    /**
     * @param UserAccessControl $uac UAC
     * @param Resource $resource Resource
     * @param array $payload Payload
     * @return ResourceCreateEvent
     */
    public static function create(UserAccessControl $uac, Resource $resource, array $payload)
    {
        return new static(self::EVENT_NAME, null, [
            'resource' => $resource,
            'payload' => $payload,
            'uac' => $uac,
        ]);
    }

    /**
     * @return UserAccessControl
     */
    public function getUac()
    {
        return $this->uac;
    }

    /**
     * @return Resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param null $value Value
     * @return Event
     * @throws Exception
     */
    public function setResult($value = null)
    {
        if (!$value instanceof Resource) {
            throw new Exception('Not good');
        }

        return parent::setResult($value);
    }

    /**
     * @return Resource
     */
    public function getResult()
    {
        return $this->resource;
    }
}
