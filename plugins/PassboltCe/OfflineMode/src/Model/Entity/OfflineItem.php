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
 * @since         5.16.0
 */
namespace Passbolt\OfflineMode\Model\Entity;

use Cake\ORM\Entity;

/**
 * OfflineItem Entity
 *
 * @property string $id
 * @property string $user_id
 * @property string $foreign_model
 * @property string $foreign_key
 * @property \Cake\I18n\DateTime $created
 * @property string $created_by
 * @property \App\Model\Entity\User|null $user
 * @property \App\Model\Entity\User|null $creator
 * @property \App\Model\Entity\Resource|null $resource
 */
class OfflineItem extends Entity
{
    /**
     * @inheritDoc
     */
    protected array $_accessible = [
        '*' => false,
    ];
}
