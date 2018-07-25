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

use Psr\Log\InvalidArgumentException;

/**
 * Directory factory class
 * @package App\Utility
 */
class ActionReport
{
    protected $model;
    protected $action;
    protected $status;
    protected $data;

    /**
     * ActionReport constructor.
     *
     * @param string $model Users, Groups, GroupsUsers
     * @param string $action SyncAction::CREATE, SyncAction::DELETE, SyncAction::UPDATE
     * @param string $status SyncAction::SUCCESS, SyncAction::ERROR, SyncAction::IGNORE
     * @param mixed $data Array or Entity, Exception
     * @throws InvalidArgumentException
     */
    public function __construct(string $model, string $action, string $status, $data)
    {
        if (!self::isValidModel($model)) {
            throw new InvalidArgumentException(__('This is not a valid action report. Invalid Model.'));
        }
        if (!self::isValidAction($action)) {
            throw new InvalidArgumentException(__('This is not a valid action report. Invalid Action: {0}', $action));
        }
        if (!self::isValidStatus($status)) {
            throw new InvalidArgumentException(__('This is not a valid action report. Invalid Status.'));
        }
        if (!self::isValidData($data)) {
            throw new InvalidArgumentException(__('This is not a valid action report. Invalid Data.'));
        }
        $this->model = $model;
        $this->data = $data;
        $this->action = $action;
        $this->status = $status;
    }

    /**
     * Status getter
     *
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Model getter
     *
     * @return string
     */
    public function getModel() {
        return $this->model;
    }

    /**
     * Action getter
     *
     * @return string
     */
    public function getAction() {
        return $this->action;
    }

    /**
     * Action validator
     *
     * @param string $action
     * @return bool
     */
    static public function isValidAction(string $action)
    {
        return ($action === SyncAction::CREATE || $action === SyncAction::UPDATE || $action === SyncAction::DELETE);
    }

    /**
     * Status validator
     *
     * @param string $status
     * @return bool
     */
    static public function isValidStatus(string $status)
    {
        return ($status === SyncAction::SUCCESS || $status === SyncAction::ERROR || $status === SyncAction::IGNORE || $status === SyncAction::SYNC);
    }

    /**
     * Model validator
     *
     * @param string $model
     * @return bool
     */
    static public function isValidModel(string $model)
    {
        return ($model === SyncAction::USERS || $model === SyncAction::GROUPS || $model === SyncAction::MEMBERS);
    }

    /**
     * Is valid data
     *
     * @param $data
     * @return bool
     */
    static public function isValidData($data)
    {
        return is_object($data) || is_array($data);
    }
}