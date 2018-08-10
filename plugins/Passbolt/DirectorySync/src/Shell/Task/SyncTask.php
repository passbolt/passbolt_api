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
 * @since         2.0.0
 */
namespace Passbolt\DirectorySync\Shell\Task;

use App\Error\Exception\ValidationException;
use App\Shell\AppShell;
use Passbolt\DirectorySync\Utility\SyncAction;

abstract class SyncTask extends AppShell
{
    protected  $model;

    protected function _displayReports($reports)
    {
        $this->hr();
        $this->out($this->model);
        $this->hr();
        $this->out(__('To create:'));
        $created = $reports->getByAction(SyncAction::CREATE);
        if (!count($created)) {
            $this->success( str_pad('[success]', 10) . __('No new item to create.'));
        }
        foreach ($created as $i => $report) {
            $this->_displayReport($report);
        }
        $this->out();
        $this->out(__('To delete:'));
        $deleted = $reports->getByAction(SyncAction::DELETE);
        if (!count($deleted)) {
            $this->success(str_pad('[success]', 10)  . __('No new item to delete'));
        }
        foreach ($deleted as $i => $report) {
            $this->_displayReport($report);
        }
        $this->out();
    }

    protected function _displayReport($report) {
        $msg = str_pad('[' . $report->getStatus() . ']', 10);
        $msg .= $report->getMessage();
        $data = $report->getData();
        switch($report->getStatus()) {
            case SyncAction::ERROR:
                $this->err($msg);
                $exception = $data->getException();
                if (isset($exception) && $data instanceof ValidationException) {
                    $this->_displayValidationError($data->getErrors());
                }
                $entry = $data->getEntity();
                if (isset($entry)) {
                    $this->out(str_pad('', 10) . __('To ignore this error in the next sync please run'));
                    $this->out(str_pad('', 10) . "./bin/cake directory_sync ignore $entry->id --model=$this->model");
                }
                break;
            case SyncAction::SYNC:
            case SyncAction::SUCCESS:
                $this->success($msg);
                break;
            case SyncAction::IGNORE:
                $this->warn($msg);
                break;
        }
    }

    /**
     * Display the entity validation errors
     *
     * @param array $errors validation errors
     * @return void
     */
    protected function _displayValidationError($errors)
    {
        foreach ($errors as $fieldname => $error) {
            foreach ($error as $rule => $message) {
                if (is_array($message)) {
                    $this->_displayValidationError($error);
                    break;
                } else {
                    $message = str_pad('', 10) . ucfirst(str_replace('_', ' ', $fieldname)) . ': ' . $message;
                    $this->err($message);
                }
            }
        }
    }
}