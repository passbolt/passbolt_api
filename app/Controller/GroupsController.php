<?php
/**
 * Groups Controller
 *
 * @copyright (c) 2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class GroupsController extends AppController {

/**
 * @var array components used by this controller
 */
	public $components = [
		'Filter',
	];

/**
 * Index entry point.
 *
 * List all groups.
 *
 * contain available:
 * - user: return the list of User and UserGroup for each group
 *   example: groups.json?contain[user]=1 or groups.json?contain=user
 *
 * - resource: return the list of Resource and Secrets (for current user) that can be accessed by the group.
 *   example: groups.json?contain[resource]=1 or groups.json?contain=resource
 *
 * filter available
 * - To document (doc available here: https://docs.google.com/document/d/1eQk9niUKnLAyIJ9y-NYtzIOuZOwMYmrIEG4rQmqJ86c/edit)
 */
	public function index() {
		// Get find options.
		$o = $this->Group->getFindOptions(
			'Group::index',
			User::get('Role.name'),
			[
				'contain' => isset($this->request->params['contain']) ? $this->request->params['contain'] : [],
				'filter' => isset($this->request->params['filter']) ? $this->request->params['filter'] : [],
			]
		);

		// Get all groups.
		$groups = $this->Group->find('all', $o);

		// Send response.
		$this->set('data', $groups);
		$this->Message->success();
	}
}