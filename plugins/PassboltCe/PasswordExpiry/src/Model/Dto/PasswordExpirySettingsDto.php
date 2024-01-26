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
 * @since         4.5.0
 */
namespace Passbolt\PasswordExpiry\Model\Dto;

use Cake\I18n\FrozenTime;
use Passbolt\PasswordExpiry\Form\PasswordExpirySettingsForm;
use Passbolt\PasswordExpiry\Model\Entity\PasswordExpirySetting;

class PasswordExpirySettingsDto
{
    public const AUTOMATIC_EXPIRY = 'automatic_expiry';
    public const AUTOMATIC_UPDATE = 'automatic_update';
    public const POLICY_OVERRIDE = 'policy_override';
    public const DEFAULT_EXPIRY_PERIOD = 'default_expiry_period';
    public const EXPIRY_NOTIFICATION = 'expiry_notification';

    protected bool $automatic_expiry;
    protected bool $automatic_update;
    protected bool $policy_override;
    protected ?int $default_expiry_period;
    protected ?int $expiry_notification;
    public ?string $id;
    public ?FrozenTime $created;
    public ?string $created_by;
    public ?FrozenTime $modified;
    public ?string $modified_by;

    /**
     * Constructor.
     *
     * @param bool $automaticExpiry Automatic expiry.
     * @param bool $automaticUpdate Automatic update.
     * @param bool $policyOverride Is policy overridable.
     * @param int|null $defaultPasswordExpiryPeriodInDays Default expiry period.
     * @param int|null $expiryNotificationInDays Number of days prior to expiry notification.
     * @param string|null $id ID.
     * @param \Cake\I18n\FrozenTime|null $created Created time.
     * @param string|null $createdBy Created by.
     * @param \Cake\I18n\FrozenTime|null $modified Modified time.
     * @param string|null $modifiedBy Modified by.
     */
    final public function __construct(
        ?bool $automaticExpiry = false,
        ?bool $automaticUpdate = false,
        ?bool $policyOverride = false,
        ?int $defaultPasswordExpiryPeriodInDays = null,
        ?int $expiryNotificationInDays = null,
        ?string $id = null,
        ?FrozenTime $created = null,
        ?string $createdBy = null,
        ?FrozenTime $modified = null,
        ?string $modifiedBy = null
    ) {
        $this->automatic_expiry = $automaticExpiry;
        $this->automatic_update = $automaticUpdate;
        $this->policy_override = $policyOverride;
        $this->default_expiry_period = $defaultPasswordExpiryPeriodInDays;
        $this->expiry_notification = $expiryNotificationInDays;
        $this->id = $id;
        $this->created = $created;
        $this->created_by = $createdBy;
        $this->modified = $modified;
        $this->modified_by = $modifiedBy;
    }

    /**
     * Returns object of itself from provided array.
     *
     * @param array $data Data.
     * @return self
     */
    public static function createFromArray(array $data = []): self
    {
        return new static(
            (bool)($data[self::AUTOMATIC_EXPIRY] ?? false),
            (bool)($data[self::AUTOMATIC_UPDATE] ?? false),
            (bool)($data[self::POLICY_OVERRIDE] ?? false),
            $data[self::DEFAULT_EXPIRY_PERIOD] ?? null,
            $data[self::EXPIRY_NOTIFICATION] ?? null,
            null,
            null,
            null,
            null,
            null
        );
    }

    /**
     * Returns object of itself from given entity.
     *
     * @param \Passbolt\PasswordExpiry\Model\Entity\PasswordExpirySetting $passwordExpirySetting Entity object to create DTO from.
     * @param \Passbolt\PasswordExpiry\Form\PasswordExpirySettingsForm $form Form having validated and modified the data.
     * @return self
     */
    public static function createFromEntity(
        PasswordExpirySetting $passwordExpirySetting,
        PasswordExpirySettingsForm $form
    ): self {
        $value = $form->getData();

        return new static(
            (bool)$value[self::AUTOMATIC_EXPIRY],
            (bool)$value[self::AUTOMATIC_UPDATE],
            (bool)($value[self::POLICY_OVERRIDE] ?? false),
            $value[self::DEFAULT_EXPIRY_PERIOD] ?? null,
            $value[self::EXPIRY_NOTIFICATION] ?? null,
            $passwordExpirySetting->id,
            $passwordExpirySetting->created,
            $passwordExpirySetting->created_by,
            $passwordExpirySetting->modified,
            $passwordExpirySetting->modified_by,
        );
    }

    /**
     * @return bool
     */
    public function isPasswordExpiryFeatureEnabled(): bool
    {
        return !is_null($this->id);
    }

    /**
     * @return bool
     */
    public function isExpiryAutomatic(): bool
    {
        return $this->automatic_expiry;
    }

    /**
     * @return array
     */
    public function getValue(): array
    {
        return [
            self::AUTOMATIC_EXPIRY => $this->automatic_expiry,
            self::AUTOMATIC_UPDATE => $this->automatic_update,
            self::POLICY_OVERRIDE => $this->policy_override,
            self::DEFAULT_EXPIRY_PERIOD => $this->default_expiry_period,
        ];
    }

    /**
     * @return array
     */
    protected function getDefaultSettingsIfFeatureIsDisabled(): array
    {
        return [
            self::AUTOMATIC_EXPIRY => false,
            self::AUTOMATIC_UPDATE => false,
        ];
    }

    /**
     * @return ?array
     */
    public function toArray(): ?array
    {
        if (!$this->isPasswordExpiryFeatureEnabled()) {
            return $this->getDefaultSettingsIfFeatureIsDisabled();
        }

        return array_merge(['id' => $this->id], $this->getValue(), [
            'created' => $this->created,
            'created_by' => $this->created_by,
            'modified' => $this->modified,
            'modified_by' => $this->modified_by,
        ]);
    }

    /**
     * @return int|null
     */
    public function getExpiryNotification(): ?int
    {
        return $this->expiry_notification;
    }
}
