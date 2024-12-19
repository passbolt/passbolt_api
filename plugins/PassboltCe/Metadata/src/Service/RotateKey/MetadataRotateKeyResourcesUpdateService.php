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
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\FrozenTime;
use Cake\ORM\Exception\PersistenceFailedException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\Metadata\Controller\RotateKey\MetadataRotateKeyResourcesIndexController;
use Passbolt\Metadata\Form\RotateKey\MetadataRotateKeyResourcesForm;

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

        $data = [];
        foreach ($requestData as $i => $values) {
            if (!is_array($values)) {
                throw new BadRequestException(__('The resource metadata key data must be an array.'));
            }

            $form = new MetadataRotateKeyResourcesForm();
            if (!$form->execute($values)) {
                $errors = [$i => $form->getErrors()];
                throw new CustomValidationException(__('Could not validate the resource metadata key data.'), $errors); // phpcs:ignore;
            }

            $data[$i] = $form->getData();
        }

        $resourcesIds = Hash::extract($data, '{n}.id');
        $resources = $this->getResourcesFromIds($resourcesIds);

        $this->updateData($uac, $resources, $data);
    }

    /**
     * @param array $resourcesIds Resource ids to find.
     * @return array
     */
    private function getResourcesFromIds(array $resourcesIds): array
    {
        $resourcesTable = TableRegistry::getTableLocator()->get('Resources');

        return $resourcesTable->find()->where([
            'id IN' => $resourcesIds,
            'deleted' => false,
        ])->toArray();
    }

    /**
     * @param \App\Utility\UserAccessControl $uac User access control.
     * @param \App\Model\Entity\Resource[] $resources Resource entities
     * @param array $data Data to update
     * @return void
     */
    private function updateData(UserAccessControl $uac, array $resources, array $data): void
    {
        /** @var \App\Model\Table\ResourcesTable $resourcesTable */
        $resourcesTable = TableRegistry::getTableLocator()->get('Resources');

        // Re-arrange resources array to set key as identifier and value as entity object to easily find it
        $resources = Hash::combine($resources, '{n}.id', '{n}');
        $entities = [];
        foreach ($data as $i => $values) {
            if (!array_key_exists($values['id'], $resources)) {
                throw new NotFoundException(__('Resource {0} not found.', $values['id']));
            }

            $resource = $resources[$values['id']];

            $this->assertData($values, $resource);

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
                throw new CustomValidationException(__('The resource metadata key data is not valid.'), $errors);
            }

            $entities[$i] = $entity;
        }

        try {
            $resourcesTable->saveManyOrFail($entities, ['checkRules' => false]);
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
        throw new CustomValidationException(__('The metadata private keys could not be created.'), $errors);
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

        if (count($requestData) >= MetadataRotateKeyResourcesIndexController::MAX_PAGINATION_LIMIT) {
            throw new BadRequestException(__('The request data is too large.'));
        }
    }

    /**
     * @param array $values Values to assert.
     * @param \App\Model\Entity\Resource $resource Existing resource entity.
     * @return void
     */
    private function assertData(array $values, Resource $resource): void
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
