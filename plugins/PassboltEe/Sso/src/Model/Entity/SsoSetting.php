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
 * @since         3.9.0
 */

namespace Passbolt\Sso\Model\Entity;

use Cake\ORM\Entity;

/**
 * SsoSetting Entity
 *
 * @property string $id
 * @property string $provider
 * @property string $data
 * @property string $status
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property string $created_by
 * @property string $modified_by
 * @property \App\Model\Entity\User $creator
 */
class SsoSetting extends Entity
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_ACTIVE = 'active';
    public const ALLOWED_STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_ACTIVE,
    ];

    /**
     * Providers.
     */
    public const PROVIDER_AZURE = 'azure';
    public const PROVIDER_GOOGLE = 'google';

    /**
     * List of supported providers.
     *
     * @var array
     */
    public const ALLOWED_PROVIDERS = [
        self::PROVIDER_AZURE,
        self::PROVIDER_GOOGLE,
    ];

    /**
     * Azure email claim alias fields.
     */
    public const AZURE_EMAIL_CLAIM_ALIAS_EMAIL = 'email';
    public const AZURE_EMAIL_CLAIM_ALIAS_PREFERRED_USERNAME = 'preferred_username';
    public const AZURE_EMAIL_CLAIM_ALIAS_UPN = 'upn';

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * For security purposes, it is advised to set '*' to false
     * and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'id' => false,
        'provider' => false,
        'status' => false,
        'data' => false,
        'created' => false,
        'modified' => false,
        'created_by' => false,
        'modified_by' => false,
    ];
}
