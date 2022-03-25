<?php
declare(strict_types=1);

namespace Passbolt\AccountRecovery\Model\Entity;

use Cake\ORM\Entity;

/**
 * AccountRecoveryRequest Entity
 *
 * @property string $id
 * @property string|null $user_id
 * @property string|null $armored_key
 * @property string|null $fingerprint
 * @property string $authentication_token_id
 * @property string $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property string $created_by
 * @property string $modified_by
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\AuthenticationToken $authentication_token
 */
class AccountRecoveryRequest extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'user_id' => true,
        'armored_key' => true,
        'fingerprint' => true,
        'authentication_token_id' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
        'created_by' => true,
        'modified_by' => true,
        'user' => true,
        'authentication_token' => true,
    ];

    public const ACCOUNT_RECOVERY_REQUEST_PENDING = 'pending';
    public const ACCOUNT_RECOVERY_REQUEST_REJECTED = 'rejected';
    public const ACCOUNT_RECOVERY_REQUEST_APPROVED = 'approved';
    public const ACCOUNT_RECOVERY_REQUEST_COMPLETED = 'completed';

    public const ACCOUNT_RECOVERY_REQUEST_STATUSES = [
        self::ACCOUNT_RECOVERY_REQUEST_PENDING,
        self::ACCOUNT_RECOVERY_REQUEST_REJECTED,
        self::ACCOUNT_RECOVERY_REQUEST_APPROVED,
        self::ACCOUNT_RECOVERY_REQUEST_COMPLETED,
    ];

    public function isPending()
    {
        return $this->status === self::ACCOUNT_RECOVERY_REQUEST_PENDING;
    }

    public function isApproved()
    {
        return $this->status === self::ACCOUNT_RECOVERY_REQUEST_APPROVED;
    }

    public function isCompleted()
    {
        return $this->status === self::ACCOUNT_RECOVERY_REQUEST_COMPLETED;
    }

    public function isRejected()
    {
        return $this->status === self::ACCOUNT_RECOVERY_REQUEST_REJECTED;
    }
}
