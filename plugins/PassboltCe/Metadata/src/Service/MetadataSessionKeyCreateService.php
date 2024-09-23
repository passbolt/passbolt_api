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
use App\Utility\UserAccessControl;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\Exception\PersistenceFailedException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Metadata\Model\Entity\MetadataSessionKey;

class MetadataSessionKeyCreateService
{
    use LocatorAwareTrait;

    /**
     * @param \App\Utility\UserAccessControl $uac UAC.
     * @param mixed $data Encrypted data to store.
     * @return \Passbolt\Metadata\Model\Entity\MetadataSessionKey
     */
    public function create(UserAccessControl $uac, $data): MetadataSessionKey
    {
        $this->assertData($data);

        /** @var \Passbolt\Metadata\Model\Table\MetadataSessionKeysTable $metadataSessionKeysTable */
        $metadataSessionKeysTable = $this->fetchTable('Passbolt/Metadata.MetadataSessionKeys');

        $metadataSessionKey = $metadataSessionKeysTable->newEntity(
            ['user_id' => $uac->getId(), 'data' => $data],
            ['accessibleFields' => ['user_id' => true, 'data' => true]]
        );
        try {
            /** @var \Passbolt\Metadata\Model\Entity\MetadataSessionKey $result */
            $result = $metadataSessionKeysTable->saveOrFail($metadataSessionKey);
        } catch (PersistenceFailedException $e) { // @phpstan-ignore-line
            $errors = $e->getEntity()->getErrors();

            throw new CustomValidationException(
                __('The metadata session key could not be saved.'),
                $errors
            );
        } catch (\Exception $e) {
            throw new InternalErrorException(
                __('Could not save the metadata session key, please try again later.'),
                null,
                $e
            );
        }

        return $result;
    }

    /**
     * Basic sanity check for the given data value.
     *
     * @param mixed $data Data to check.
     * @return void
     */
    private function assertData($data): void
    {
        if (!is_string($data)) {
            throw new BadRequestException(__('The data must be a string.'));
        }
    }
}
