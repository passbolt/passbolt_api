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
 * @since         4.11.0
 */
namespace Passbolt\Metadata\Service\RotateKey;

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\Resource;
use App\Utility\UserAccessControl;
use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosInterface;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ConflictException;
use Cake\Http\Exception\InternalErrorException;
use Cake\I18n\FrozenTime;
use Cake\ORM\Exception\PersistenceFailedException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\TableRegistry;
use Passbolt\Metadata\Controller\Component\MetadataPaginationComponent;
use Passbolt\Metadata\Model\Validation\MetadataResourcesBatchUpdateValidationService;

class MetadataRotateKeyResourcesUpdateService
{
    use EventDispatcherTrait;
    use LocatorAwareTrait;

    /**
     * @param \App\Utility\UserAccessControl $uac UAC.
     * @param array $requestData Request data.
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException If data is invalid.
     * @throws \App\Error\Exception\CustomValidationException If data is invalid.
     * @throws \Cake\Http\Exception\NotFoundException If one or more resources are not found.
     */
    public function updateMany(UserAccessControl $uac, array $requestData): void
    {
        $uac->assertIsAdmin();
        $this->assertRequestData($requestData);

        $metadataBatchValidationService = new MetadataResourcesBatchUpdateValidationService();
        $data = $metadataBatchValidationService->validateMany($requestData);
        $this->updateData($uac, $data, $metadataBatchValidationService->getEntities());
    }

    /**
     * @param \App\Utility\UserAccessControl $uac User access control.
     * @param array $data Data to update
     * @param \App\Model\Entity\Resource[] $resources Resource entities
     * @return void
     */
    private function updateData(UserAccessControl $uac, array $data, array $resources): void
    {
        /** @var \App\Model\Table\ResourcesTable $resourcesTable */
        $resourcesTable = TableRegistry::getTableLocator()->get('Resources');

        $entities = [];
        foreach ($data as $i => $values) {
            $resource = $resources[$values['id']];

            $this->assertConflict($values, $resource);

            $values = array_merge($values, [
                'modified' => FrozenTime::now(),
                'modified_by' => $uac->getId(),
            ]);
            $entity = $resourcesTable->patchEntity($resource, $values, [
                'accessibleFields' => [
                    'metadata_key_id' => true,
                    'metadata_key_type' => true,
                    'metadata' => true,
                    'modified' => true,
                    'modified_by' => true,
                ],
                'validate' => 'v5',
            ]);

            /** @var \Cake\ORM\RulesChecker $rules */
            $rules = $resourcesTable->rulesChecker();
            $resourcesTable->buildRulesV5($rules);

            if ($entity->getErrors()) {
                $errors = [$i => $entity->getErrors()];
                throw new CustomValidationException(__('The resource metadata key data could not be updated.'), $errors); // phpcs:ignore
            }

            $entities[$i] = $entity;
        }

        try {
            $resourcesTable->saveManyOrFail($entities);
        } catch (PersistenceFailedException $exception) { // @phpstan-ignore-line
            $this->handleSaveManyValidationException($exception, $entities);
        } catch (\Exception $exception) {
            throw new InternalErrorException(
                __('The resource metadata key data could not be updated.'),
                null,
                $exception
            );
        }
    }

    /**
     * Throw exception for the entity by preserving the array index of entity.
     *
     * @param \Cake\ORM\Exception\PersistenceFailedException $exception Exception object.
     * @param array $entities Entities were being stored.
     * @return void
     * @throws \App\Error\Exception\CustomValidationException
     */
    protected function handleSaveManyValidationException(PersistenceFailedException $exception, array $entities): void
    {
        $index = 0;

        $failedEntity = $exception->getEntity();
        // We find index by looping through each entity since cakephp doesn't provide us,
        // and can't be done at early stage due being in buildRules.
        foreach ($entities as $i => $entity) {
            // @see https://www.php.net/manual/en/language.oop5.object-comparison.php
            if ($failedEntity === $entity) {
                $index = $i;
                break;
            }
        }

        $errors = [$index => $exception->getEntity()->getErrors()];
        throw new CustomValidationException(__('The resource metadata key data could not be updated.'), $errors);
    }

    /**
     * @param array $requestData Request data.
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException If data could not be asserted.
     */
    private function assertRequestData(array $requestData): void
    {
        if (empty($requestData)) {
            throw new BadRequestException(__('The request data should not be empty.'));
        }

        if (count($requestData) > MetadataPaginationComponent::MAX_PAGINATION_LIMIT) {
            throw new BadRequestException(__('The request data is too large.'));
        }
    }

    /**
     * Checks for modified datetime and modified by to make sure it's not been changed by other user.
     *
     * @param array $values Values to assert.
     * @param \App\Model\Entity\Resource $resource Existing resource entity.
     * @return void
     * @throws \Cake\Http\Exception\ConflictException If provided values do not match with the ones present in the DB.
     */
    private function assertConflict(array $values, Resource $resource): void
    {
        // Assert modified date hasn't been changed
        $modified = $values['modified'] instanceof ChronosInterface ? $values['modified'] : new Chronos($values['modified']); // phpcs:ignore
        if ($modified->toDateTimeString() !== $resource->get('modified')->toDateTimeString()) { // we are comparing via toDateTimeString() to avoid microsecond difference
            throw new ConflictException(__('The provided modified date does not match.'));
        }

        // Assert modified by hasn't been changed
        if ($resource->get('modified_by') !== $values['modified_by']) {
            throw new ConflictException(__('The provided modified by does not match.'));
        }
    }
}
