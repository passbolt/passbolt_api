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
 * @since         3.3.0
 */

namespace Passbolt\Mobile\Model\Entity;

use Cake\ORM\Entity;

/**
 * Class Transfer
 *
 * @property string $id
 * @property string $user_id
 * @property int $current_page
 * @property int $total_pages
 * @property string $hash
 * @property string $status
 * @property \DateTimeInterface $created
 * @property \DateTimeInterface $modified
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\AuthenticationToken $authentication_token
 * @package Passbolt\Mobile\Model\Entity
 */
class Transfer extends Entity
{
    public const TRANSFER_STATUS_START = 'start';
    public const TRANSFER_STATUS_IN_PROGRESS = 'in progress';
    public const TRANSFER_STATUS_COMPLETE = 'complete';
    public const TRANSFER_STATUS_CANCEL = 'cancel';
    public const TRANSFER_STATUS_ERROR = 'error';

    /**
     * Allowed statuses
     */
    public const TRANSFER_STATUSES = [
        self::TRANSFER_STATUS_START,
        self::TRANSFER_STATUS_IN_PROGRESS,
        self::TRANSFER_STATUS_COMPLETE,
        self::TRANSFER_STATUS_CANCEL,
        self::TRANSFER_STATUS_ERROR,
    ];

    /**
     * Transfer Hash
     */
    //public const TRANSFER_HASH_ALGORITHM = 'SHA512';
    public const TRANSFER_HASH_SIZE = 128;
    public const TRANSFER_MAX_PAGES = 65535; // mysql small int

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     * For security purposes, we set all to false by default to explicitly make
     * individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'id' => false,
        'current_page' => false,
        'total_pages' => false,
        'hash' => false,
        'status' => false,

        // associated data
        'user_id' => false,
        'user' => false,
        'authentication_token' => false,
        'authentication_token_id' => false,
    ];
}
