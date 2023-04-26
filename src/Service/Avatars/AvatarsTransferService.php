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
 * @since         3.2.0
 */
namespace App\Service\Avatars;

use App\Model\Table\AvatarsTable;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Log\Log;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Table;
use Laminas\Diactoros\UploadedFile;

/**
 * Class AvatarsTransferService
 *
 * @deprecated Will be removed with version 4
 * This service was used to migrate avatars from
 * version prior to 3.2 to 3.2.
 */
class AvatarsTransferService
{
    use LocatorAwareTrait;

    /**
     * @var \App\Model\Table\AvatarsTable
     */
    protected $Avatars;

    /**
     * @var \Cake\ORM\Table
     */
    protected $FileStorage;

    /**
     * @var bool
     */
    protected $debugMode;

    /**
     * @var array
     */
    protected $results = [];

    /**
     * AvatarsTransferService constructor.
     *
     * @param \App\Model\Table\AvatarsTable $Avatars Avatars table
     * @param \Cake\ORM\Table $FileStorage File Storage table
     * @param bool $debugMode if true, do not delete file storage nor persist avatars.
     */
    public function __construct(AvatarsTable $Avatars, Table $FileStorage, bool $debugMode = false)
    {
        $this->Avatars = $Avatars;
        $this->FileStorage = $FileStorage;
        $this->debugMode = $debugMode;
        $this->results = [
            'success' => [],
            'error' => [],
        ];
    }

    /**
     * Copy the entities in file storage into the avatars table.
     * If not in debug mode, the file storage entities shall be deleted
     * on successful copy.
     *
     * In debug mode, returns the errors and successes in $this->results array.
     *
     * @return array|array[]
     */
    public function transfer(): array
    {
        $fileStorages = $this->FileStorage->find()->where(['model' => 'Avatar']);

        foreach ($fileStorages as $fileStorage) {
            $filePath = $fileStorage->get('path');
            if (empty($filePath)) {
                $id = $fileStorage->id;
                $this->logError("The file storage with id {$id} has no path defined.");
                continue;
            }
            $uploadedFile = $this->getUploadedFile($filePath);
            if ($uploadedFile !== false) {
                $avatar = $this->Avatars->newEntity([
                    'profile_id' => $fileStorage->foreign_key,
                    'file' => $uploadedFile,
                ], ['validate' => false]);

                if ($avatar->hasErrors()) {
                    $this->logError("The avatar with file {$filePath} has validation error.");
                    continue;
                }

                try {
                    $saveResult = $this->Avatars->save($avatar);
                } catch (\Throwable $e) {
                    $this->logError($e->getMessage());
                    continue;
                }

                if ($saveResult === false) {
                    if ($avatar->getError('profile_id')) {
                        $this->logError(
                            'Profile with id ' . $avatar->profile_id . ' not found. The avatar could not be saved.'
                        );
                    } else {
                        $this->logError("The avatar with file {$filePath} could not be saved.");
                    }
                    continue;
                } elseif ($this->debugMode === true) {
                    // In debug mode, the avatars table is left empty.
                    try {
                        $this->Avatars->delete($avatar);
                    } catch (\Throwable $e) {
                        $this->logError($e->getMessage());
                    }
                }

                if ($this->debugMode !== true) {
                    $this->deleteFileStorage($fileStorage, $filePath);
                }

                $this->logSuccess("The file {$filePath} was successfully transferred from file storage to avatars.");
            }
        }

        return $this->results;
    }

    /**
     * Creates an uploaded file object.
     *
     * @param string $path Path to the file storage file.
     * @return false|\Laminas\Diactoros\UploadedFile
     */
    private function getUploadedFile(string $path)
    {
        try {
            if (defined('PASSBOLT_ORG')) {
                $originFileSystem = $this->Avatars->getFilesystem();
                $stream = $originFileSystem->readStream($path);
                $fileSize = $originFileSystem->fileSize($path);
            } else {
                $stream = $this->getCleanFilePath($path);
                $fileSize = file_exists($stream) && is_readable($stream) ? filesize($stream) : 0;
            }

            return new UploadedFile(
                $stream,
                $fileSize,
                \UPLOAD_ERR_OK
            );
        } catch (\Throwable $e) {
            $this->logError("The file {$path} could not be read.");

            return false;
        }
    }

    /**
     * @param string $path Path to clean.
     * @return string
     */
    private function getCleanFilePath(string $path): string
    {
        if (strpos(substr($path, 0, 1), '/', 0) === false) {
            // Relative path
            $path = Configure::read('ImageStorage.basePath') . ltrim($path, './'); // Cleanup relative paths with dot at the beginning
        } else {
            // Absolute path
            $path = str_replace('//', '/', $path); // Cleanup absolute path to avoid double slash
        }

        return $path;
    }

    /**
     * Deletes a file storage entity and the associated file.
     *
     * @param \Cake\Datasource\EntityInterface $fileStorage File storage to delete.
     * @param string $filePath Path to the file to delete.
     * @return void
     */
    private function deleteFileStorage(EntityInterface $fileStorage, string $filePath): void
    {
        if ($this->FileStorage->delete($fileStorage) === false) {
            $id = $fileStorage->get('id');
            $this->logError("The file storage with id {$id} could not be deleted.");
        }

        try {
            $this->Avatars->getFilesystem()->delete($filePath);
        } catch (\Throwable $e) {
            $this->logError("The file {$filePath} could not be deleted.");
        }
    }

    /**
     * @param string $msg Message to log.
     * @return void
     */
    private function logError(string $msg): void
    {
        Log::error($msg);
        if ($this->debugMode) {
            $this->results['error'][] = $msg;
        }
    }

    /**
     * @param string $msg Message to log.
     * @return void
     */
    private function logSuccess(string $msg): void
    {
        if ($this->debugMode) {
            $this->results['success'][] = $msg;
        }
    }
}
