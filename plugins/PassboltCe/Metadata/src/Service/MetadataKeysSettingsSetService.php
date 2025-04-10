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
 * @since         4.10.0
 */
namespace Passbolt\Metadata\Service;

use App\Error\Exception\CustomValidationException;
use App\Error\Exception\ValidationException;
use App\Utility\UserAccessControl;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Exception\BadRequestException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Validation\Validation;
use Passbolt\Metadata\Model\Dto\MetadataKeysSettingsDto;

class MetadataKeysSettingsSetService
{
    use EventDispatcherTrait;
    use LocatorAwareTrait;

    public const AFTER_METADATA_SETTINGS_SET_SUCCESS_EVENT_NAME = 'MetadataSettings.afterSettingSet.success';

    /**
     * Validates and save the metadata settings
     *
     * @param \App\Utility\UserAccessControl $uac user access control
     * @param array $data Data provided in the payload
     * @return \Passbolt\Metadata\Model\Dto\MetadataKeysSettingsDto dto
     * @throws \Cake\Http\Exception\UnauthorizedException When user role is not admin.
     * @throws \App\Error\Exception\CustomValidationException When there are validation errors.
     * @throws \Cake\Http\Exception\InternalErrorException|\Exception When unable to save the entity.
     * @throws \App\Error\Exception\FormValidationException if the data does not validate
     */
    public function saveSettings(UserAccessControl $uac, array $data): MetadataKeysSettingsDto
    {
        $uac->assertIsAdmin();

        $dto = (new MetadataKeysSettingsAssertService())->assert($data);

        $metadataKeysTable = $this->fetchTable('Passbolt/Metadata.MetadataKeys');
        $keysCount = $metadataKeysTable->find()
            ->where(['deleted IS NOT NULL'])
            ->all()
            ->count();

        if ($keysCount && !$dto->isKeyShareZeroKnowledge()) {
            /** @var \Passbolt\Metadata\Model\Table\MetadataPrivateKeysTable $metadataPrivateKeysTable */
            $metadataPrivateKeysTable = $this->fetchTable('Passbolt/Metadata.MetadataPrivateKeys');
            $serverKeysCount = $metadataPrivateKeysTable->find()
                ->where(['user_id IS' => null])
                ->orderBy(['created' => 'DESC'])
                ->all()
                ->count();
            if ($serverKeysCount === 0) {
                if (
                    !isset($data['metadata_private_keys']) ||
                    !is_array($data['metadata_private_keys']) ||
                    !count($data['metadata_private_keys'])
                ) {
                    $msg = __('The server metadata private key is required to enable these settings.');
                    throw new BadRequestException($msg);
                }

                $service = new MetadataPrivateKeysCreateService();
                foreach ($data['metadata_private_keys'] as $i => $key) {
                    if (!isset($key['metadata_key_id']) || !Validation::uuid($key['metadata_key_id'])) {
                        $msg = __('The server metadata key id is required.');
                        throw new BadRequestException($msg);
                    }
                    try {
                        $service->create($uac, $key['metadata_key_id'], $key);
                    } catch (ValidationException $exception) {
                        $msg = __('The server metadata private key is invalid.');
                        $errors['metadata_private_keys'][$i] = $exception->getErrors();
                        throw new CustomValidationException($msg, $errors);
                    }
                }
            }
        }

        /** @var \App\Model\Table\OrganizationSettingsTable $orgSettingsTable */
        $orgSettingsTable = $this->fetchTable('OrganizationSettings');
        $orgSettingsTable->createOrUpdateSetting(
            MetadataKeysSettingsGetService::ORG_SETTING_PROPERTY,
            $dto->toJson(),
            $uac
        );

        $this->dispatchEvent(
            static::AFTER_METADATA_SETTINGS_SET_SUCCESS_EVENT_NAME,
            compact('dto', 'uac'),
            $this
        );

        return $dto;
    }
}
