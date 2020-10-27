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
 * @since         2.2.0
 */
namespace Passbolt\AccountSettings\Model\Table\Traits;

use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Cake\Http\Exception\InternalErrorException;
use Cake\Log\Log;
use Cake\Routing\Router;
use Cake\Utility\Hash;
use Cake\Validation\Validator;

trait ThemeSettingsTrait
{
    /**
     * Default validation rules for theme settings.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    protected function themeValidationDefault(Validator $validator)
    {
        $validator
            ->add('value', ['isValidTheme' => [
                'on' => function ($context) {
                    return (isset($context['data']['property']) && $context['data']['property'] === 'theme');
                },
                'rule' => [$this, 'isValidTheme'],
                'message' => __('This theme is not supported.'),
            ]]);

        return $validator;
    }

    /**
     * Custom validation rule to validate account setting property name
     *
     * @param string $value fingerprint
     * @param array $context not in use
     * @return bool
     */
    public function isValidTheme(string $value, array $context = null)
    {
        return in_array($value, Hash::extract($this->findAllThemes(), "{n}.name"));
    }

    /**
     * Get the theme to apply
     *
     * @param string $userId uuid
     * @return string value of the theme setting or default
     */
    public function getTheme($userId)
    {
        try {
            $theme = $this->getFirstPropertyOrFail($userId, 'theme');
        } catch (RecordNotFoundException $exception) {
            return null;
        }
        $theme = $theme->toArray();

        return $theme['value'];
    }

    /**
     * Return the list of valid themes
     *
     * @return array
     */
    public function findAllThemes()
    {
        $defaultCssFileName = Configure::read('passbolt.plugins.accountSettings.themes.css');
        $themesPath = WWW_ROOT . 'css' . DS . 'themes';

        $dir = new Folder($themesPath);
        $files = $dir->read(true, true);
        if (!isset($files[0])) {
            throw new InternalErrorException(__('No themes installed.'));
        }
        $response = [];
        foreach ($files[0] as $dir) {
            $cssFilePath = $themesPath . DS . $dir . DS . $defaultCssFileName;
            $defaultPreviewImageName = $dir . '.png';
            $imagePreviewFilePath = IMAGES . DS . 'themes' . DS . $defaultPreviewImageName;
            $cssFile = new File($cssFilePath);
            $imagePreviewFile = new File($imagePreviewFilePath);
            if ($cssFile->exists() && $imagePreviewFile->exists()) {
                $response[] = [
                    'id' => UuidFactory::uuid('theme.id.' . $dir),
                    'name' => $dir,
                    'preview' => Router::url('/img/themes/' . $defaultPreviewImageName, true),
                ];
            } else {
                $msg = __('ThemesIndexController: Could not load theme {0}, the main css file or preview image is missing', $dir);
                Log::error($msg);
            }
        }

        return $response;
    }
}
