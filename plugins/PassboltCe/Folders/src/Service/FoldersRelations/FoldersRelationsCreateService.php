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
 * @since         2.13.0
 */

namespace Passbolt\Folders\Service\FoldersRelations;

use App\Error\Exception\ValidationException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Folders\Model\Entity\FoldersRelation;

class FoldersRelationsCreateService
{
    use LocatorAwareTrait;

    /**
     * @var \Passbolt\Folders\Model\Table\FoldersRelationsTable
     */
    private $foldersRelationsTable;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        /** @phpstan-ignore-next-line */
        $this->foldersRelationsTable = $this->fetchTable('Passbolt/Folders.FoldersRelations');
    }

    /**
     * Create a folder relation.
     *
     * @param array $folderRelationData The folder relation data
     * @param bool $checkRules (optional) Should the table rules be checked while saving the entity. Default true.
     * @return \Passbolt\Folders\Model\Entity\FoldersRelation
     * @throws \Exception If an unexpected error occurred
     */
    public function create(array $folderRelationData, ?bool $checkRules = true): FoldersRelation
    {
        $folderRelation = $this->buildFolderRelationEntity($folderRelationData);
        $this->handleFolderRelationValidationErrors($folderRelation);
        $this->foldersRelationsTable->save($folderRelation, ['checkRules' => $checkRules]);
        $this->handleFolderRelationValidationErrors($folderRelation);

        return $folderRelation;
    }

    /**
     * Build the folder relation entity.
     *
     * @param array $folderRelationData The folder relation data
     * @return \Passbolt\Folders\Model\Entity\FoldersRelation
     */
    private function buildFolderRelationEntity(array $folderRelationData): FoldersRelation
    {
        $accessibleFields = [
            'foreign_model' => true,
            'foreign_id' => true,
            'user_id' => true,
            'folder_parent_id' => true,
        ];

        return $this->foldersRelationsTable->newEntity($folderRelationData, ['accessibleFields' => $accessibleFields]);
    }

    /**
     * Handle folder relation validation errors.
     *
     * @param \Passbolt\Folders\Model\Entity\FoldersRelation $folderRelation The folder relation
     * @return void
     */
    private function handleFolderRelationValidationErrors(FoldersRelation $folderRelation): void
    {
        $errors = $folderRelation->getErrors();
        if (!empty($errors)) {
            $msg = __('Could not validate folder relation data.');
            throw new ValidationException($msg, $folderRelation, $this->foldersRelationsTable);
        }
    }
}
