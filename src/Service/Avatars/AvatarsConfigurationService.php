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
 * @since         3.3.2
 */
namespace App\Service\Avatars;

use Cake\Core\Configure;

class AvatarsConfigurationService
{
    public const FORMAT_SMALL = 'small';
    public const FORMAT_MEDIUM = 'medium';

    /**
     * Load the basic image configuration.
     * This method should be called in the
     * bootstrap of the application in order
     * to ensure that these configs are always present.
     * These configs are useful for example in the rendering
     * of emails.
     *
     * @return void
     */
    public function loadConfiguration(): void
    {
        // File storage and images
        Configure::write('ImageStorage.basePath', WWW_ROOT . 'img' . DS . 'public' . DS);
        Configure::write('ImageStorage.publicPath', 'img' . DS . 'public' . DS);
        Configure::write('FileStorage', [
            'imageDefaults' => [
                'Avatar' => [
                    self::FORMAT_MEDIUM => 'img' . DS . 'avatar' . DS . 'user_medium.png',
                    self::FORMAT_SMALL => 'img' . DS . 'avatar' . DS . 'user.png',
                ],
            ],
            // Configure image versions on a per model base
            'imageSizes' => [
                'Avatar' => [
                    self::FORMAT_MEDIUM => [
                        'thumbnail' => [
                            'mode' => 'outbound',
                            'width' => 200,
                            'height' => 200,
                        ],
                    ],
                    self::FORMAT_SMALL => [
                        'thumbnail' => [
                            'mode' => 'outbound',
                            'width' => 80,
                            'height' => 80,
                        ],
                        'crop' => [
                            'width' => 80,
                            'height' => 80,
                        ],
                    ],
                ],
            ],
        ]);
    }
}
