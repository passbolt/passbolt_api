<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */

namespace App\Controller\Favorites;

use App\Controller\AppController;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\NotFoundException;
use Cake\Validation\Validation;

class FavoritesAddController extends AppController
{
    /**
     * Mark a resource as favorite.
     *
     * @param string $foreignId The identifier of the instance to mark as favorite.
     * @throws BadRequestException
     * @throws InternalErrorException
     * @throws NotFoundException
     */
    public function add($foreignId = null)
    {
        // Check request sanity
        if (!Validation::uuid($foreignId)) {
            throw new BadRequestException(__('The resource id is not valid.'));
        }
        $this->loadModel('Favorites');
        $this->loadModel('Resources');

        // Build and validate the favorite entity.
        $favorite = $this->_buildAndValidateFavorite($foreignId);
        if ($this->_handleValidationError($favorite)) {
            return;
        }

        // Save the favorite
        $result = $this->Favorites->save($favorite, ['checkRules' => false, 'atomic' => false]);
        $this->set('favorite', $favorite);
        if (!$result) {
            throw new InternalErrorException(__('The resource cannot be marked as favorite.'));
        }
        $this->_handleValidationError($favorite);
        $this->success(__('The resource was marked as favorite.'), $favorite);
    }

    /**
     * Manage validation errors.
     *
     * @param \Cake\Datasource\EntityInterface $favorite favorite
     * @return bool
     */
    protected function _handleValidationError($favorite)
    {
        // If validation fails and request is json return the validation errors
        // Otherwise render the registration form with the errors
        $errors = $favorite->getErrors();
        if (!empty($errors)) {
            throw new BadRequestException(__('Could not validate favorite data.'));
        }
    }

    /**
     * Build and validate favorite entity from user input.
     *
     * @param string $foreignId The identifier of the instance to mark as favorite.
     * @return \Cake\Datasource\EntityInterface $favorite favorite entity
     */
    protected function _buildAndValidateFavorite($foreignId = null) {
        // Build entity and perform basic check.
        $favorite = $this->Favorites->newEntity(
            [
                'user_id' => $this->User->id(),
                'foreign_id' => $foreignId,
                'foreign_model' => 'Resource',
            ],
            [
                'validate' => 'default',
                'accessibleFields' => [
                    'user_id' => true,
                    'foreign_id' => true,
                    'foreign_model' => true
                ]
            ]
        );

        // No need to check rules if basic validation fails.
        if ($favorite->getErrors()) {
            return $favorite;
        }
        $this->Favorites->checkRules($favorite);

        return $favorite;
    }
}
