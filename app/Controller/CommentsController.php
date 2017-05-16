<?php
/**
 * Comments Controller
 *
 * @copyright (c) 2015-2016 Bolt Softwares Pvt Ltd
 *            (c) 2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class CommentsController extends AppController {

	public $components = [
		'EmailNotificator'
	];

/**
 * View comments for a given model name and record id
 *
 * @param string $foreignModelName the foreign model
 * @param string $foreignId the uuid of the record to get comments for
 * @throws MethodNotAllowedException if http request method is not GET
 * @throws BadRequestException if comments are not allowed on $foreignModelName
 * @throws BadRequestException if the resource id is missing or not a valid UUID
 * @throws NotFoundException if the resource id does not exist
 * @throws NotFoundException if the user does not have read permission on the resource id
 * @return void
 */
	public function viewForeignComments($foreignModelName = null, $foreignId = null) {
		$foreignModelName = Inflector::camelize($foreignModelName);

		// Check request sanity
		if (!$this->request->is('get')) {
			throw new MethodNotAllowedException(__('Invalid request method, should be GET.'));
		}
		if (!$this->Comment->isValidForeignModel($foreignModelName)) {
			throw new BadRequestException(__('Comments are not possible on this type of resource (%s).', $foreignModelName));
		}
		if (is_null($foreignId)) {
			throw new BadRequestException(__('The resource id is missing.'));
		}
		if (!Common::isUuid($foreignId)) {
			throw new BadRequestException(__('The resource id is not valid.'));
		}
		if (!$this->Comment->{$foreignModelName}->exists($foreignId)) {
			throw new NotFoundException(__('The resource does not exist.'));
		}
		if (!$this->Comment->{$foreignModelName}->isAuthorized($foreignId, PermissionType::READ)) {
			throw new NotFoundException(__('The resource does not exist.'));
		}

		// Find and return the comments
		$findData = ['Comment' => ['foreign_id' => $foreignId]];
		$findOptions = $this->Comment->getFindOptions('viewByForeignModel', User::get('Role.name'), $findData);
		$comments = $this->Comment->find('threaded', $findOptions);
		$this->set('data', $comments);
		$this->Message->success();
	}

/**
 * Add comment to a given model name and record id
 *
 * @param string $foreignModelName The target foreign model
 * @param string $foreignId The uuid of the target instance to create comments for
 * @throws MethodNotAllowedException if http request method is not POST
 * @throws BadRequestException if comments are not allowed on $foreignModelName
 * @throws BadRequestException if resource id is missing or not a valid UUID
 * @throws NotFoundException if the resource id does not exist
 * @throws NotFoundException if the user does not have read permission on the resource id
 * @throws BadRequestException if the comment data can not be validated
 * @throws InternalErrorException if the save operation failed
 * @return void
 */
	public function addForeignComment($foreignModelName = null, $foreignId = null) {
		$foreignModelName = Inflector::camelize($foreignModelName);
		$postData = $this->request->data;

		// Check request sanity
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException(__('Invalid request method, should be POST.'));
		}
		if (!$this->Comment->isValidForeignModel($foreignModelName)) {
			throw new BadRequestException(__('Comments are not possible on this type of resource (%s).', $foreignModelName));
		}
		if (is_null($foreignId)) {
			throw new BadRequestException(__('The resource id is missing.'));
		}
		if (!Common::isUuid($foreignId)) {
			throw new BadRequestException(__('The resource id is not valid.'));
		}
		if (!$this->Comment->{$foreignModelName}->exists($foreignId)) {
			throw new NotFoundException(__('The resource does not exist.'));
		}
		if (!$this->Comment->{$foreignModelName}->isAuthorized($foreignId, PermissionType::READ)) {
			throw new NotFoundException(__('The resource does not exist.'));
		}
		if (!isset($postData['Comment'])) {
			throw new BadRequestException(__('No comment data provided.'));
		}

		// Validate comment data
		$postData['Comment']['foreign_model'] = $foreignModelName;
		$postData['Comment']['foreign_id'] = $foreignId;
		$this->Comment->create();
		$this->Comment->set($postData);
		if (!$this->Comment->validates()) {
			throw new BadRequestException(__('Could not validate comment data.'));
		}

		// Save
		$fields = $this->Comment->getFindFields('add', User::get('Role.name'));
		$comment = $this->Comment->save($postData, true, $fields['fields']);
		if ($comment === false) {
			throw new InternalErrorException('The comment could not be added. Please try again later.');
		}

		// Handle email notifications.
		$AcoModel = Common::getModel(ucfirst($foreignModelName));
		$authorizedUsers = $AcoModel->findAuthorizedUsers($foreignId);
		$authorizedUsersIds = Hash::extract($authorizedUsers, '{n}.User.id');
		foreach ($authorizedUsersIds as $userId) {
			// Do not send a notification to user who wrote the comment.
			if ($userId != User::get('id')) {
				$this->EmailNotificator->passwordCommentNotification(
					$userId,
					[
						'resource_id' => $foreignId,
						'comment_id' => $this->Comment->id,
					]);
			}
		}

		// return the newly inserted comment
		$findData = ['Comment' => ['id' => $this->Comment->id]];
		$findOptions = $this->Comment->getFindOptions('view', User::get('Role.name'), $findData);
		$this->set('data', $this->Comment->find('first', $findOptions));
		$this->Message->success(__('The comment was successfully added.'));
	}

/**
 * Edit a comment
 * Only the owner of the comment can edit it
 *
 * @param string $id the uuid of the comment to edit
 * @throws MethodNotAllowedException if http request method is not PUT
 * @throws BadRequestException if comment id is missing or not a valid UUID
 * @throws NotFoundException if the comment does not exist
 * @throws ForbiddenException if the user is not the owner of the comment
 * @throws BadRequestException if the comment data can not be validated
 * @throws InternalErrorException if the save operation failed
 * @return void
 */
	public function edit($id = null) {
		// Check request sanity
		if (!$this->request->is('put')) {
			throw new MethodNotAllowedException(__('Invalid request method, should be PUT.'));
		}
		if (is_null($id)) {
			throw new BadRequestException(__('The comment id is missing.'));
		}
		if (!Common::isUuid($id)) {
			throw new BadRequestException(__('The comment id is not valid.'));
		}
		if (!$this->Comment->exists($id)) {
			throw new NotFoundException(__('The comment does not exist.'));
		}
		if (!$this->Comment->isOwner($id)) {
			throw new ForbiddenException(__('You are not allowed to edit this comment.'));
		}

		// Validate
		$data = $this->request->data;
		$data['Comment']['id'] = $id;
		$this->Comment->create();
		$this->Comment->setValidationRules('edit');
		$this->Comment->set($data);
		if (!$this->Comment->validates()) {
			throw new BadRequestException(__('Could not validate comment data.'));
		}

		// Try to save
		$fields = $this->Comment->getFindFields('edit', User::get('Role.name'));
		$comment = $this->Comment->save($data, true, $fields['fields']);
		if ($comment === false) {
			throw new InternalErrorException('The comment could not be updated.');
		}

		// Return comment data
		$findData = ['Comment' => ['id' => $this->Comment->id]];
		$findOptions = $this->Comment->getFindConditions('view', User::get('Role.name'), $findData);
		$this->set('data', $this->Comment->find('first', $findOptions));
		$this->Message->success(__('The comment was successfully updated.'));
	}

/**
 * Delete a comment
 * Only the owner of the comment can delete it
 * Deleting a comment will also delete the children comments
 *
 * @param string $id the uuid of the comment to edit
 * @throws MethodNotAllowedException if http request method is not DELETE
 * @throws BadRequestException if the comment id is missing or not a valid UUID
 * @throws NotFoundException if the comment does not exist
 * @throws ForbiddenException if the user is not the owner of the comment
 * @throws InternalErrorException if the delete operation failed
 * @return void
 */
	public function delete($id = null) {
		// Check request sanity
		if (!$this->request->is('delete')) {
			throw new MethodNotAllowedException(__('Invalid request method, should be DELETE.'));
		}
		if (is_null($id)) {
			throw new BadRequestException(__('The comment id is missing.'));
		}
		if (!Common::isUuid($id)) {
			throw new BadRequestException(__('The comment id is not valid.'));
		}
		if (!$this->Comment->exists($id)) {
			throw new NotFoundException(__('The comment does not exist.'));
		}
		if (!$this->Comment->isOwner($id)) {
			throw new ForbiddenException(__('You are not allowed to delete this comment.'));
		}

		// Delete the target comment and by cascading its children
		if (!$this->Comment->delete($id, true)) {
			throw new InternalErrorException('The comment could not be deleted.');
		}
		$this->Message->success(__('The comment was successfully deleted.'));
	}
}
