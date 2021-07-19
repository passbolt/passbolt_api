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
namespace Passbolt\DirectorySync\Command;

use App\Error\Exception\ValidationException;
use Cake\Console\ConsoleIo;
use Passbolt\DirectorySync\Actions\Reports\ActionReport;
use Passbolt\DirectorySync\Utility\Alias;
use Passbolt\DirectorySync\Utility\SyncError;

trait SyncCommandTrait
{
    /**
     * @var string
     */
    protected $model;

    /**
     * @var int
     */
    protected $pad = 10;

    /**
     * Display reports
     *
     * @param \Passbolt\DirectorySync\Actions\Reports\ActionReportCollection $reports list of reports
     * @param string $model Model concerned.
     * @param \Cake\Console\ConsoleIo $io Console IO.
     * @return void
     */
    protected function displayReports($reports, string $model, ConsoleIo $io)
    {
        $io->hr();
        $io->out($model);
        $io->hr();
        $io->out(__('Created:'));
        $created = $reports->getByAction(Alias::ACTION_CREATE);
        if (!count($created)) {
            $this->success(str_pad('[success]', $this->pad) . __('No new item to create.'), $io);
        }
        foreach ($created as $i => $report) {
            $this->displayReport($report, $io);
        }
        $io->out();
        $io->out(__('Deleted:'));
        $deleted = $reports->getByAction(Alias::ACTION_DELETE);
        if (!count($deleted)) {
            $this->success(str_pad('[success]', $this->pad) . __('No new item to delete'), $io);
        }
        foreach ($deleted as $i => $report) {
            $this->displayReport($report, $io);
        }
        $io->out();
        $io->info(
            __(
                'For more explanation on sync error messages, see: {0}',
                ['https://help.passbolt.com/configure/ldap/ldap-common-sync-error-messages']
            )
        );
        $io->out();
    }

    /**
     * Display report
     *
     * @param \Passbolt\DirectorySync\Actions\Reports\ActionReport $report report
     * @param \Cake\Console\ConsoleIo $io Console IO.
     * @return void
     */
    protected function displayReport(ActionReport $report, ConsoleIo $io)
    {
        $msg = str_pad('[' . $report->getStatus() . ']', $this->pad);
        $msg .= $report->getMessage();
        $data = $report->getData();
        switch ($report->getStatus()) {
            case Alias::STATUS_ERROR:
                $io->err($msg);
                if ($data instanceof SyncError) {
                    $exception = $data->getException();
                    if ($exception instanceof ValidationException) {
                        $this->displayValidationError($exception->getErrors(), $io);
                        $id = $exception->getEntity()->id;
                        $model = $this->model;
                    } else {
                        $id = $data->getEntity()->id;
                        $model = 'DirectoryEntries';
                    }

                    $p = str_pad('', $this->pad);
                    $io->out($p . __('To ignore this error in the next sync please run'));
                    $io->out($p . "./bin/cake directory_sync ignore-create --id=$id --model=$model");
                }
                break;
            case Alias::STATUS_SYNC:
            case Alias::STATUS_SUCCESS:
                $this->success($msg, $io);
                break;
            case Alias::STATUS_IGNORE:
                $io->warning($msg);
                break;
        }
    }

    /**
     * Display the entity validation errors
     *
     * @param array $errors validation errors
     * @param \Cake\Console\ConsoleIo $io Console IO.
     * @return void
     */
    protected function displayValidationError($errors, ConsoleIo $io): void
    {
        foreach ($errors as $fieldname => $error) {
            foreach ($error as $rule => $message) {
                if (is_array($message)) {
                    $this->displayValidationError($error, $io);
                    break;
                } else {
                    $message = str_pad('', $this->pad) . ucfirst(str_replace('_', ' ', $fieldname)) . ': ' . $message;
                    $io->err($message);
                }
            }
        }
    }
}
