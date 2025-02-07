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
 * @since         4.12.0
 */
namespace Passbolt\Metadata\Service\RotateKey;

use App\Error\Exception\CustomValidationException;
use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosInterface;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ConflictException;
use Cake\ORM\Entity;
use Cake\ORM\Exception\PersistenceFailedException;
use Passbolt\Metadata\Controller\Component\MetadataPaginationComponent;

trait MetadataRotateKeyUpdateServiceTrait
{
    /**
     * Throw exception for the entity by preserving the array index of entity.
     *
     * @param \Cake\ORM\Exception\PersistenceFailedException $exception Exception object.
     * @param array $entities Entities were being stored.
     * @param string $errorMessage Validation error message
     * @return void
     * @throws \App\Error\Exception\CustomValidationException
     */
    protected function handleSaveManyValidationException(
        PersistenceFailedException $exception,
        array $entities,
        string $errorMessage
    ): void {
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
        throw new CustomValidationException($errorMessage, $errors);
    }

    /**
     * @param array $requestData Request data.
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException If data could not be asserted.
     */
    protected function assertRequestData(array $requestData): void
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
     * @param \Passbolt\Folders\Model\Entity\Folder|\App\Model\Entity\Resource $entity Existing entity.
     * @return void
     * @throws \Cake\Http\Exception\ConflictException If provided values do not match with the ones present in the DB.
     */
    private function assertConflict(array $values, Entity $entity): void
    {
        if (!$entity->has('modified_by') || !$entity->has('modified')) {
            return;
        }
        // Assert modified date hasn't been changed
        $modified = $values['modified'] instanceof ChronosInterface ? $values['modified'] : new Chronos($values['modified']); // phpcs:ignore
        if ($modified->toDateTimeString() !== $entity->get('modified')->toDateTimeString()) { // we are comparing via toDateTimeString() to avoid microsecond difference
            throw new ConflictException(__('The provided modified date does not match.'));
        }

        // Assert modified by hasn't been changed
        if ($entity->get('modified_by') !== $values['modified_by']) {
            throw new ConflictException(__('The provided modified by does not match.'));
        }
    }
}
