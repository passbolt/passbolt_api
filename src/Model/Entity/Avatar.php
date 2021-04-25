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
 * @since         2.0.0
 */
namespace App\Model\Entity;

use App\View\Helper\AvatarHelper;
use Cake\Core\Configure;
use Cake\ORM\Entity;

/**
 * @property string $id
 * @property string|resource|null $data
 * @property string $profile_id
 * @property \Cake\I18n\FrozenTime $created_at
 * @property \Cake\I18n\FrozenTime|null $updated_at
 * @property \App\Model\Entity\Profile $profile
 * @property-read array $url
 */
class Avatar extends Entity
{
    protected $_virtual = ['url'];

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     *
     * Note that when '*' is set to false, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
    ];

    /**
     * Url virtual field implementation.
     *
     * @return array
     */
    protected function _getUrl()
    {
        $sizes = Configure::read('FileStorage.imageSizes.Avatar');
        $avatarsPath = [];
        // Add path for each available size.
        foreach ($sizes as $size => $filters) {
            $avatarsPath[$size] = AvatarHelper::getAvatarUrl($this, $size);
        }

        // Transform original model to add paths.
        return $avatarsPath;
    }
}
