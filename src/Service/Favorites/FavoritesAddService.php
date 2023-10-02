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
 * @since         3.9.0
 */
namespace App\Service\Favorites;

use App\Error\Exception\ValidationException;
use App\Model\Entity\Favorite;
use App\Utility\UserAccessControl;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;

class FavoritesAddService
{
    /**
     * @var \App\Model\Table\FavoritesTable
     */
    private $Favorites;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->Favorites = TableRegistry::getTableLocator()->get('Favorites');
    }

    /**
     * Marks a resource as favorite.
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation.
     * @param string $foreignKey The identifier of the instance to mark as favorite.
     * @return \App\Model\Entity\Favorite `Favorite` entity if saved successfully.
     * @throws \Cake\Http\Exception\BadRequestException If the record is already marked as favorite.
     * @throws \Cake\Http\Exception\NotFoundException If the resource does not exist.
     * @throws \Cake\Http\Exception\NotFoundException If the resource is soft deleted.
     * @throws \Cake\Http\Exception\NotFoundException If the user does not have access to the resource.
     */
    public function add(UserAccessControl $uac, string $foreignKey): Favorite
    {
        // Build and validate the favorite entity.
        $favorite = $this->_buildAndValidateFavorite($uac, $foreignKey);

        // Save the favorite
        $result = $this->Favorites->save($favorite);
        $this->_handleValidationError($favorite);

        return $favorite;
    }

    /**
     * Build and validate favorite entity from user input.
     *
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation.
     * @param string $foreignKey The identifier of the instance to mark as favorite.
     * @return \App\Model\Entity\Favorite $favorite Favorite entity.
     * @throws \Cake\Http\Exception\BadRequestException If the record is already marked as favorite.
     * @throws \Cake\Http\Exception\NotFoundException If the resource does not exist.
     * @throws \App\Error\Exception\ValidationException If validation failed.
     */
    protected function _buildAndValidateFavorite(UserAccessControl $uac, string $foreignKey): Favorite
    {
        // Build entity and perform basic check.
        $favorite = $this->Favorites->newEntity(
            [
                'user_id' => $uac->getId(),
                'foreign_key' => $foreignKey,
                'foreign_model' => 'Resource',
            ],
            [
                'accessibleFields' => [
                    'user_id' => true,
                    'foreign_key' => true,
                    'foreign_model' => true,
                ],
            ]
        );

        // Handle validation errors if any at this stage.
        $this->_handleValidationError($favorite);

        return $favorite;
    }

    /**
     * Manage validation errors.
     *
     * @param \App\Model\Entity\Favorite $favorite favorite
     * @throws \Cake\Http\Exception\BadRequestException if the record is already marked as favorite
     * @throws \Cake\Http\Exception\NotFoundException if the resource does not exist
     * @throws \App\Error\Exception\ValidationException if validation failed
     * @return void
     */
    protected function _handleValidationError(Favorite $favorite)
    {
        $errors = $favorite->getErrors();

        if (!empty($errors)) {
            if (
                isset($errors['foreign_key']['resource_exists'])
                || isset($errors['foreign_key']['resource_is_not_soft_deleted'])
                || isset($errors['foreign_key']['has_resource_access'])
            ) {
                throw new NotFoundException(__('The resource does not exist.'));
            }

            if (isset($errors['user_id']['favorite_unique'])) {
                throw new BadRequestException(__('This record is already marked as favorite.'));
            }

            throw new ValidationException(__('Could not validate favorite data.'));
        }
    }
}
