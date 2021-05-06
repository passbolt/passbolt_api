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
 * @since         3.0.0
 */

use App\Utility\Filesystem\DirectoryUtility;
use Cake\Core\Configure;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Laminas\Diactoros\UploadedFile;
use League\Flysystem\FilesystemException;
use Migrations\AbstractMigration;

class V320CopyFileStorageToAvatars extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function up()
    {
        $FileStoragesTable = TableRegistry::getTableLocator()
            ->get('FileStorages')
            ->setTable('file_storage');

        /** @var \App\Model\Table\AvatarsTable $AvatarsTable */
        $AvatarsTable = TableRegistry::getTableLocator()->get('Avatars');

        $fileStorages = $FileStoragesTable->find()->where(['model' => 'Avatar']);

        $dropFileStorage = true;

        $fileSystem = $AvatarsTable->getFilesystem();
        // Wrap this in a class for command and migrations
        foreach ($fileStorages as $fileStorage) {
            $filePath = $this->getCleanFilePath($fileStorage->path);
            try {
                $stream = $fileSystem->readStream($filePath);
                $fileSize = $fileSystem->fileSize($filePath);
                $fileIsReadable = true;
            } catch (\Throwable $e) {
                $fileIsReadable = false;
                $stream = null;
                $fileSize = null;
                // TODO: Add log
            }
            if ($fileIsReadable) {
                $avatar = $AvatarsTable->newEntity([
                    'profile_id' => $fileStorage->foreign_key,
                    'file' =>  new UploadedFile(
                        $stream,
                        $fileSize,
                        \UPLOAD_ERR_OK,
                        $fileStorage->filename,
                    ),
                ], ['validate' => false]);

                if (!$AvatarsTable->save($avatar)) {
                    $dropFileStorage = false;
                    Log::warning(__('The file_storage table will not be dropped.'), $filePath);
                } else {
                    $FileStoragesTable->delete($fileStorage);
                }
                try {
                    $fileSystem->delete($filePath);
                } catch (FilesystemException $e) {
                    Log::warning(__('The avatar located at {0} could not be deleted.'), $filePath);
                }
            }
        }

        if ($dropFileStorage) {
            $this->table('file_storage')->drop()->save();
            $legacyAvatarFolder = Configure::read('ImageStorage.basePath') . 'Avatar';
            $removeSuccessful = DirectoryUtility::removeRecursively($legacyAvatarFolder);
            if ($removeSuccessful === false) {
                Log::warning(__('The folder {0} could not be removed.'), $legacyAvatarFolder);
            }
        }
    }

    public function getCleanFilePath(string $path): string
    {
        if (strpos(substr($path, 0, 1), '/', 0) === false) {
            // Relative path
            $path = Configure::read('ImageStorage.basePath') . ltrim($path, './'); // Cleanup relative paths with dot at the beginning
        } else {
            // Absolute path
            $path = ltrim($path, '/'); // Cleanup absolute path to avoid double slash
        }

        return $path;
    }

    public function down()
    {}
}
