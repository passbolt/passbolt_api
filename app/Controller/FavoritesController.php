<?php
/**
 * Favorites controller
 * Control the starred items
 *
 * @copyright (c) 2015-2016 Bolt Softwares Pvt Ltd
 *                2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class FavoritesController extends AppController {

/**
 * Add a favorite for a target model instance
 *
 * @param string $foreignModelName the target foreign model
 * @param string $foreignId the uuid of the target instance to get comments for
 * @throws MethodNotAllowedException if the request is not POST
 * @throws BadRequestException if favorites are not enabled for $foreignModelName
 * @throws BadRequestException if the $foreignId is not a valid UUID
 * @throws NotFoundException if the $foreignId does not exist
 * @throws BadRequestException if the record is already stared
 * @throws InternalErrorException if the favorite could not be saved
 * @return void
 */
	public function add($foreignModelName = null, $foreignId = null) {
		$foreignModelName = Inflector::camelize($foreignModelName);
		$userId = User::get('id');

		// check the request sanity
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException(__('Invalid request method, should be POST.'));
		}
		if (!$this->Favorite->isValidForeignModel($foreignModelName)) {
			throw new BadRequestException(__('Favorites are not possible on this type of resource (%s).', $foreignModelName));
		}
		if (!Common::isUuid($foreignId)) {
			throw new BadRequestException(__('The resource id is not valid.'));
		}
		$this->loadModel($foreignModelName);
		if (!$this->{$foreignModelName}->exists($foreignId)) {
			throw new NotFoundException(__('The resource does not exist.'));
		}
		$favorite = $this->Favorite->find('first', ['conditions' => ['user_id' => $userId, 'foreign_id' => $foreignId]]);
		if (!empty($favorite)) {
			throw new BadRequestException(__('This record is already marked as favorite.'));
		}

		// Add the favorite
		$this->Favorite->create([
			'user_id' => $userId,
			'foreign_id' => $foreignId,
			'foreign_model' => strtolower($foreignModelName)
		]);
		$favorite = $this->Favorite->save();
		if ($favorite === false) {
			throw new InternalErrorException(__('The favorite could not be added.'));
		}
		$this->set('data', $favorite);
		$this->Message->success(__('This record was successfully starred!'));
	}

/**
 * Unfav/unstar a given record for a given model
 *
 * @param string $id the uuid of the resource
 * @throws MethodNotAllowedException if the request method is not DELETE
 * @throws BadRequestException if the resource id is not a valid UUID
 * @throws NotFoundException if the favorite id does not exist
 * @throws ForbiddenException if the current user is not the owner of the favorite
 * @throws InternalErrorException if the favorite could not be deleted
 * @return void
 */
	public function delete($id = null) {
		// check the request sanity
		if (!$this->request->is('delete')) {
			throw new MethodNotAllowedException(__('Invalid request method, should be DELETE.'));
		}
		if (!Common::isUuid($id)) {
			throw new BadRequestException(__('The resource id is not valid.'));
		}
		$favorite = $this->Favorite->findById($id);
		if (empty($favorite)) {
			throw new NotFoundException(__('The favorite does not exist.'));
		}
		if ($favorite['Favorite']['user_id'] != User::get('id')) {
			throw new ForbiddenException(__('Your are not allowed to delete this favorite.'));
		}

		// Delete
		if (!$this->Favorite->delete($id)) {
			throw new InternalErrorException('The favorite could not be deleted.');
		}
		$this->Message->success(__('This record was removed from your starred item list'));
	}
}