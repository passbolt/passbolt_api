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
 * @since         2.7.0
 */

use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Filesystem\Folder;
use Cake\ORM\TableRegistry;
use Migrations\AbstractMigration;

class V280FileDirectoryPathsMigrations extends AbstractMigration
{
    /**
     * Up
     *
     * @return void
     */
    public function up()
    {
        $connectionName = 'default';
        if ($this->input->getOption('connection')) {
            $connectionName = $this->input->getOption('connection');
        }
        $connection = ConnectionManager::get($connectionName);

        $Avatars = TableRegistry::getTableLocator()->get('Avatars', ['connection' => $connection]);
        $avatars = $Avatars->find()->all();
        $publicPath = WWW_ROOT . Configure::read('ImageStorage.publicPath');

        foreach ($avatars as $oldAvatar) {
            // get original path
            $originalFilePath = $publicPath . $oldAvatar->path;
            $strippedId = str_replace('-', '', $oldAvatar->id);
            $originalFileName = $strippedId . '.' . $oldAvatar->extension;
            $originalFile = $originalFilePath . $originalFileName;

            $connection->delete('file_storage', ['id' => $oldAvatar->id]);

            if (!file_exists($originalFile)) {
                // Avatar file cannot be found
                echo __("Avatar file not found for user {0}, resetting to default.\n", $oldAvatar->user_id);
            } else {
                // Create new avatar from old file
                $data = [
                    'file' => [
                        'tmp_name' => $originalFile,
                        'error' => 0,
                        'name' => $originalFileName,
                    ],
                    'user_id' => $oldAvatar->user_id,
                    'foreign_key' => $oldAvatar->foreign_key,
                ];

                $newAvatar = $Avatars->newEntity($data, ['validate' => false]);
                if (!$Avatars->save($newAvatar)) {
                    echo __("Could not save avatar for user {0}, resetting to default.\n", $oldAvatar->user_id);
                }
            }
        }
        $oldFolder = new Folder($publicPath . 'images');
        $oldFolder->delete();
    }

    /**
     * Down
     *
     * @return void
     */
    public function down()
    {
        return;
    }
}
