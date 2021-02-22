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
 * @since         2.0.0
 */
namespace Passbolt\DirectorySync\Shell\Task;

use App\Error\Exception\ValidationException;
use App\Shell\AppShell;
use Passbolt\DirectorySync\Utility\Alias;
use Passbolt\DirectorySync\Utility\SyncError;

abstract class SyncTask extends AppShell
{
    protected $model;
    protected $pad = 10;

    /**
     * Display reports
     *
     * @param array $reports list of reports
     * @return void
     */
    protected function _displayReports($reports)
    {
        $this->hr();
        $this->out($this->model);
        $this->hr();
        $this->out(__('Created:'));
        $created = $reports->getByAction(Alias::ACTION_CREATE);
        if (!count($created)) {
            $this->success(str_pad('[success]', $this->pad) . __('No new item to create.'));
        }
        foreach ($created as $i => $report) {
            $this->_displayReport($report);
        }
        $this->out();
        $this->out(__('Deleted:'));
        $deleted = $reports->getByAction(Alias::ACTION_DELETE);
        if (!count($deleted)) {
            $this->success(str_pad('[success]', $this->pad) . __('No new item to delete'));
        }
        foreach ($deleted as $i => $report) {
            $this->_displayReport($report);
        }
        $this->out();
        $this->info(
            __(
                'For more explanation on sync error messages, see: {0}',
                ['https://help.passbolt.com/configure/ldap/ldap-common-sync-error-messages']
            )
        );
        $this->out();
    }

    /**
     * Display report
     *
     * @param \Passbolt\DirectorySync\Shell\Task\ActionReport $report report
     * @return void
     */
    protected function _displayReport($report)
    {
        $msg = str_pad('[' . $report->getStatus() . ']', $this->pad);
        $msg .= $report->getMessage();
        $data = $report->getData();
        switch ($report->getStatus()) {
            case Alias::STATUS_ERROR:
                $this->err($msg);
                if ($data instanceof SyncError) {
                    $exception = $data->getException();
                    if ($exception instanceof ValidationException) {
                        $this->_displayValidationError($exception->getErrors());
                        $id = $exception->getEntity()->id;
                        $model = $this->model;
                    } else {
                        $id = $data->getEntity()->id;
                        $model = 'DirectoryEntries';
                    }

                    $p = str_pad('', $this->pad);
                    $this->out($p . __('To ignore this error in the next sync please run'));
                    $this->out($p . "./bin/cake directory_sync ignore-create --id=$id --model=$model");
                }
                break;
            case Alias::STATUS_SYNC:
            case Alias::STATUS_SUCCESS:
                $this->success($msg);
                break;
            case Alias::STATUS_IGNORE:
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
                    $message = str_pad('', $this->pad) . ucfirst(str_replace('_', ' ', $fieldname)) . ': ' . $message;
                    $this->err($message);
                }
            }
        }
    }
}
