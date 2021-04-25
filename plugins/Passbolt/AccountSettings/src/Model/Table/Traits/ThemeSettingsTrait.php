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
 * @since         2.2.0
 */
namespace Passbolt\AccountSettings\Model\Table\Traits;

use App\Utility\UuidFactory;
use Cake\Datasource\Exception\RecordNotFoundException;
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
                    return isset($context['data']['property']) && $context['data']['property'] === 'theme';
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
    public function isValidTheme(string $value, ?array $context = null)
    {
        return in_array($value, Hash::extract($this->findAllThemes(), '{n}.name'));
    }

    /**
     * Get the theme to apply
     *
     * @param string|null $userId uuid
     * @return string|null value of the theme setting or default
     */
    public function getTheme(?string $userId): ?string
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
        $defaultCssFileName = 'api_main.min.css';
        $themesPath = WWW_ROOT . 'css' . DS . 'themes';

        $themeFolders = array_diff(scandir($themesPath), ['.', '..']);
        if (empty($themeFolders)) {
            throw new InternalErrorException('No themes installed.');
        }
        $response = [];
        foreach ($themeFolders as $dir) {
            $cssFilePath = $themesPath . DS . $dir . DS . $defaultCssFileName;
            $defaultPreviewImageName = $dir . '.png';
            $imagePreviewFilePath = IMAGES . DS . 'themes' . DS . $defaultPreviewImageName;
            if (file_exists($cssFilePath) && file_exists($imagePreviewFilePath)) {
                $response[] = [
                    'id' => UuidFactory::uuid('theme.id.' . $dir),
                    'name' => $dir,
                    'preview' => Router::url('/img/themes/' . $defaultPreviewImageName, true),
                ];
            } else {
                $msg = "ThemesIndexController: Could not load theme $dir. ";
                $msg .= 'The main css file or preview image is missing';
                Log::error($msg);
            }
        }

        return $response;
    }
}
