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
namespace Passbolt\UsersSettings\Controller\Themes;

use App\Controller\AppController;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use Cake\Log\Log;
use Cake\Network\Exception\InternalErrorException;
use Cake\Routing\Router;

class ThemesIndexController extends AppController
{
    /**
     * Roles Index action
     *
     * @return void
     */
    public function index()
    {
        $defaultCssFileName = 'api_main.min.css';
        $themesPath = WWW_ROOT . 'css' . DS . 'themes';
        $dir = new Folder($themesPath);
        $files = $dir->read(true, true);
        if (!isset($files[0])) {
            throw new InternalErrorException(__('No themes installed.'));
        }
        $response = [];
        foreach($files[0] as $dir) {
            $cssFilePath = $themesPath . DS . $dir . DS . $defaultCssFileName;
            $defaultPreviewImageName = $dir . '.png';
            $imagePreviewFilePath = IMAGES . DS . 'themes' . DS . $defaultPreviewImageName;
            $cssFile = new File($cssFilePath);
            $imagePreviewFile = new File($imagePreviewFilePath);
            if ($cssFile->exists() && $imagePreviewFile->exists()) {
                $response[] = [
                    'name' => $dir,
                    'preview' => Router::url('/img/themes/' . $defaultPreviewImageName, true)
                ];
            } else {
                $msg = __('Could not load theme {0} the main css file or preview image is missing', $dir);
                Log::error($msg);
            }
        }
        $this->success(__('The operation was successful.'), $response);
    }
}
