<?php
declare(strict_types=1);

namespace Passbolt\AccountRecovery\Model\Entity;

use Cake\ORM\Entity;

/**
 * AccountRecoveryResponse Entity
 *
 * @property string $id
 * @property string $account_recovery_requests_id
 * @property string $responder_foreign_key
 * @property string $responder_foreign_model
 * @property string|null $data
 * @property string $status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property string $created_by
 * @property string $modified_by
 */
class AccountRecoveryResponse extends Entity
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
        'responder_foreign_key' => false,
        'responder_foreign_model' => false,
        'data' => false,
        'status' => false,
        'created' => false,
        'modified' => false,
        'created_by' => false,
        'modified_by' => false,
        'account_recovery_requests_id' => false,
    ];

    public const STATUS_REJECTED = 'rejected';
    public const STATUS_APPROVED = 'approved';

    public const RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY = 'AccountRecoveryOrganizationKey';
    //public const RECIPIENT_FOREIGN_MODEL_RECOVERY_CONTACT = 'AccountRecoveryContact';
    public const ALLOWED_RECIPIENT_FOREIGN_MODELS = [
        self::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY,
        //self::RECIPIENT_FOREIGN_MODEL_RECOVERY_CONTACT
    ];

    public const STATUSES = [
        self::STATUS_REJECTED,
        self::STATUS_APPROVED,
    ];
}
