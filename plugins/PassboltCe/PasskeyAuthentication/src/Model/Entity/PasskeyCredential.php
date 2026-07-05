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
 * @since         5.14.0
 */
namespace Passbolt\PasskeyAuthentication\Model\Entity;

use Cake\ORM\Entity;

/**
 * PasskeyCredential Entity
 *
 * @property string $id
 * @property string $user_id
 * @property string $credential_id
 * @property string $data
 * @property string|null $server_kit_key
 * @property int $sign_count
 * @property string|null $transports
 * @property string|null $aaguid
 * @property string|null $name
 * @property \Cake\I18n\DateTime|null $last_used
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 *
 * @property \App\Model\Entity\User $user
 */
class PasskeyCredential extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * The credential material and the server half of the kit are set server-side only (never from a
     * client payload as-is), so they are intentionally NOT accessible; only the user provided
     * friendly name can be patched.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'name' => true,
        '*' => false,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * Both the authoritative credential material (`data`) and the server half of the passphrase kit
     * (`server_kit_key`) must never leak through entity serialization: the server half is returned
     * only by the login finish endpoint, explicitly, and only after a valid assertion.
     *
     * @var list<string>
     */
    protected array $_hidden = [
        'data',
        'server_kit_key',
    ];
}
