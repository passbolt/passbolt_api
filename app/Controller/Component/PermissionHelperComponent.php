<?php
/**
 * PermissionHelper Component
 * This class offers tools for controllers who needs to access permissions.
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class PermissionHelperComponent extends Component {

/**
 * @var $Permission Model
 */
	public $Permission;

/**
 * Called before the Controller::beforeFilter().
 *
 * @param Controller $controller Controller with components to initialize
 * @return void
 * @link http://book.cakephp.org/2.0/en/controllers/components.html#Component::initialize
 */
	public function initialize(Controller $controller) {
		$this->Permission = Common::getModel('Permission');
	}

/**
 * Get list of ACO permissions.
 *
 * @param string $acoModelName model name
 * @param string $acoInstanceId UUID of the aco instance
 * @throws InvalidArgumentException if parameters are empty or incorrect
 * @return array
 */
	public function findAcoPermissions($acoModelName = '', $acoInstanceId = null) {
		$permissions = [];

		// check if the target ACO model is permissionable
		if (!$this->Permission->isValidAco($acoModelName)) {
			throw new InvalidArgumentException(__('The model %s is not permissionable', $acoModelName));
		}

		// no aco instance id given
		if (is_null($acoInstanceId)) {
			throw new InvalidArgumentException(__('The %s id is missing', $acoModelName));
		}

		// the aco instance id is invalid
		if (!Common::isUuid($acoInstanceId)) {
			throw new InvalidArgumentException(__('The %s id is invalid', $acoModelName));
		}

		// the aco instance does not exist
		$this->$acoModelName = Common::getModel($acoModelName);

		// Here the permissionable behavior will filter data to avoid user who are not allowed
		// not allowed => Permission.type < PermissionType::READ
		// If the user is allowed to access the instance, he is allowed to list the permissions
		// that are applied on it.
		$acoInstance = $this->$acoModelName->findById($acoInstanceId);
		if (!empty($acoInstance)) {
			// case used by find options functions
			// this view case has to be defined in model which represent the views
			$viewCase = 'viewBy' . $acoModelName;

			// get user's permissions for the target instance
			$viewName = 'User' . $acoModelName . 'Permission';
			$ModelView = Common::getModel($viewName);
			$foreignKey = Inflector::underscore($acoModelName) . '_id';
			$upData = [
				$viewName => [
					$foreignKey => $acoInstanceId
				]
			];
			$upOptions = $ModelView->getFindOptions($viewCase, User::get('Role.name'), $upData);
			$ups = $ModelView->find('all', $upOptions);

			// merge user's and group's permissions
			$permissions = array_merge($ups);
		}

		return $permissions;
	}

/**
 * Get a list of users who can access to the given ACO, with their corresponding permissions.
 *
 * @param string $acoModelName model name
 * @param string $acoInstanceId UUID of the aco instance
 * @return array
 */
	public function findAcoUsers($acoModelName, $acoInstanceId) {
		$AcoModel = Common::getModel($acoModelName);
		return $AcoModel->getUsersWithAPermissionSet($acoInstanceId);
	}
}