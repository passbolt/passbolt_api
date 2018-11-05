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
namespace App\Model\Entity;

use Burzum\FileStorage\Model\Entity\ImageStorage;
use Cake\Core\Configure;
use Cake\Event\Event;

class Avatar extends ImageStorage
{
    protected $_virtual = ['url'];

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * We keep the same as the FileStorage module. The only difference is that the hash
     * field is inaccessible.
     *
     * Note that when '*' is set to false, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'filename' => true,
        'model' => true,
        'foreign_key' => true,
        'file' => true,
        'old_file_id' => true,
        'hash' => false,
        // Keep this true for the next major release because it's a BC breaker
        '*' => true,
    ];

    /**
     * Url virtual field implementation.
     * @return array
     */
    protected function _getUrl()
    {
        $sizes = Configure::read('FileStorage.imageSizes.Avatar');
        $avatarsPath = [];
        // Add path for each available size.
        foreach ($sizes as $size => $filters) {
            $url = $this->getAvatarUrl($this, $size);
            $avatarsPath[$size] = $url ? $url : '';
        }
        // Transform original model to add paths.
        return $avatarsPath;
    }

    /**
     * Get avatar url for a specific size.
     * @param \App\Model\Entity\Avatar $avatar the avatar entity
     * @param string $version image version (small or medium)
     * @param array $options options
     * @return bool|string
     */
    public function getAvatarUrl($avatar, $version = null, $options = [])
    {
        // Default options.
        $defaultOptions = [
            'version' => 'small',
        ];
        $options = array_merge($options, $defaultOptions);

        // If image is empty, we return the default avatar.
        if (empty($avatar) || empty($avatar->id)) {
            // Return fallback images.
            $avatarDefaults = Configure::read('FileStorage.imageDefaults.Avatar');
            if (isset($avatarDefaults[$version])) {
                return $avatarDefaults[$version];
            }

            return false;
        }

        if (!empty($version)) {
            $hash = Configure::read('FileStorage.imageHashes.' . $avatar->model . '.' . $version);
            if (empty($hash)) {
                if (empty($avatar->model) || !isset($avatar->model)) {
                    $avatar->model = 'undefined';
                }
                throw new \InvalidArgumentException(
                    __d(
                        'file_storage',
                        'No valid version key (%s %s) passed!',
                        $avatar->model,
                        $version
                    )
                );
            }
        } else {
            $hash = null;
        }

        $Event = new Event('ImageVersion.getVersions', $this, [
                'hash' => $hash,
                'image' => $avatar,
                'version' => $version,
                'options' => $options
            ]);
        $this->getEventManager()->dispatch($Event);

        if ($Event->isStopped()) {
            return Configure::read('ImageStorage.publicPath') . $this->normalizePath($Event->data['path']);
        } else {
            return false;
        }
    }

    /**
     * Turns the windows DS into linux one
     * E.g. \ into / so that the path can be used in an url
     *
     * @param string $path a file path like\this\one
     * @return string a file path like/this/one
     */
    public function normalizePath($path)
    {
        return str_replace('\\', '/', $path);
    }
}
