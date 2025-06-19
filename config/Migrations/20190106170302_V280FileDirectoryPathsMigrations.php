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

use App\Utility\AvatarProcessing;
use App\Utility\Filesystem\DirectoryUtility;
use Cake\Core\Configure;
use Cake\Log\Log;
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
        $avatars = $this->getSelectBuilder()->select('*')->from('file_storage')->all();
        $publicPath = WWW_ROOT . Configure::read('ImageStorage.publicPath');

        foreach ($avatars as $oldAvatar) {
            try {
                // get original path
                $originalFilePath = $publicPath . $oldAvatar['path'];
                $strippedId = str_replace('-', '', $oldAvatar['id']);
                $originalFileName = $strippedId . '.' . $oldAvatar['extension'];
                $originalFile = $originalFilePath . $originalFileName;

                $this->getDeleteBuilder()->from('file_storage')->where(['id' => $oldAvatar['id']])->execute();

                if (!file_exists($originalFile)) {
                    // Avatar file cannot be found
                    echo __("Avatar file not found for user {0}, resetting to default.\n", $oldAvatar['user_id']);
                    continue;
                }

                $content = file_get_contents($originalFile);
                $img = AvatarProcessing::resizeAndCrop(
                    $content,
                    Configure::readOrFail('FileStorage.imageSizes.Avatar.medium.thumbnail.width'),
                    Configure::readOrFail('FileStorage.imageSizes.Avatar.medium.thumbnail.height')
                );

                // Create new avatar from old file
                $data = [
                    'data' => $img,
                    'user_id' => $oldAvatar['user_id'],
                    'foreign_key' => $oldAvatar['foreign_key'],
                ];

                $newAvatar = $this
                    ->getInsertBuilder()
                    ->insert(['data', 'user_id', 'foreign_key'])
                    ->into('file_storage')
                    ->values($data)
                    ->rowCountAndClose();
                if ($newAvatar === 0) {
                    echo __("Could not save avatar for user {0}, resetting to default.\n", $oldAvatar['user_id']);
                }
            } catch (\Exception $e) {
                $msg = '[V280FileDirectoryPathsMigrations] Unable to process avatar. ';
                $msg .= $e->getMessage();
                Log::error($msg);
            }
        }

        DirectoryUtility::removeRecursively($publicPath . 'images');
    }

    /**
     * Down
     *
     * @return void
     */
    public function down()
    {
    }
}
