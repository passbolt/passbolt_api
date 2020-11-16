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
namespace Passbolt\DirectorySync\Actions\Reports;

use Cake\I18n\FrozenTime;
use Passbolt\DirectorySync\Utility\Alias;

/**
 * Directory factory class
 *
 * @package App\Utility
 */
class ActionReport implements \Serializable
{
    protected $model;
    protected $action;
    protected $status;
    protected $data;
    protected $created;
    protected $message;

    /**
     * ActionReport constructor.
     *
     * @param string $message message
     * @param string $model Users, Groups, GroupsUsers
     * @param string $action see Alias::ACTION_*
     * @param string $status see Alias::STATUS_*
     * @param mixed $data Array or Entity, Exception
     * @throws \InvalidArgumentException
     */
    public function __construct(string $message, string $model, string $action, string $status, $data)
    {
        if (!self::isValidModel($model)) {
            throw new \InvalidArgumentException(__('This is not a valid action report. Invalid Model.'));
        }
        if (!self::isValidAction($action)) {
            throw new \InvalidArgumentException(__('This is not a valid action report. Invalid Action: {0}', $action));
        }
        if (!self::isValidStatus($status)) {
            throw new \InvalidArgumentException(__('This is not a valid action report. Invalid Status.'));
        }
        if (!self::isValidData($data)) {
            throw new \InvalidArgumentException(__('This is not a valid action report. Invalid Data.'));
        }
        $this->message = $message;
        $this->model = $model;
        $this->data = $data;
        $this->action = $action;
        $this->status = $status;
        $this->created = FrozenTime::now();
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize([
            'message' => $this->message,
            'model' => $this->model,
            'data' => $this->data,
            'action' => $this->action,
            'status' => $this->status,
            'created' => $this->created,
            'version' => '2',
        ]);
    }

    /**
     * Unserialize.
     *
     * @param string $serialized serialized
     * @return void
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        foreach ($data as $key => $value) {
            if (in_array($key, ['message', 'model', 'data', 'action', 'status', 'created'])) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * Transform ActionReport to array.
     *
     * @return array the action report transformed.
     */
    public function toArray()
    {
        return [
            'message' => $this->message,
            'model' => $this->model,
            'data' => is_object($this->data) && method_exists($this->data, 'toArray') ? $this->data->toArray() : [],
            'action' => $this->action,
            'status' => $this->status,
            'created' => $this->created,
            'version' => '2',
        ];
    }

    /**
     * Status getter
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Status getter
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Model getter
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Action getter
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Action getter
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Creation datetime getter
     *
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Action validator
     *
     * @param string $action action
     * @return bool
     */
    public static function isValidAction(string $action)
    {
        return $action === Alias::ACTION_CREATE || $action === Alias::ACTION_UPDATE || $action === Alias::ACTION_DELETE;
    }

    /**
     * Status validator
     *
     * @param string $status status
     * @return bool
     */
    public static function isValidStatus(string $status)
    {
        return $status === Alias::STATUS_SUCCESS
            || $status === Alias::STATUS_ERROR
            || $status === Alias::STATUS_IGNORE
            || $status === Alias::STATUS_SYNC;
    }

    /**
     * Model validator
     *
     * @param string $model model
     * @return bool
     */
    public static function isValidModel(string $model)
    {
        return $model === Alias::MODEL_USERS
            || $model === Alias::MODEL_GROUPS
            || $model === Alias::MODEL_GROUPS_USERS
            || $model === SyncAction::MEMBERS;
    }

    /**
     * Is valid data
     *
     * @param array $data data
     * @return bool
     */
    public static function isValidData($data)
    {
        return is_object($data) || is_array($data);
    }
}
