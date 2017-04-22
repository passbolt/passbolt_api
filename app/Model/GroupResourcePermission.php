<?php
/**
 * GroupResourcePermission Model
 *
 * @copyright (c) 2017-present Passbolt SARl
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class GroupResourcePermission extends AppModel {

/**
 * Use permissions table.
 * @var string
 */
	public $useTable = 'permissions';

/**
 * Details of belongs to relationships
 *
 * @link http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#
 */
	public $belongsTo = [
		'PermissionType' => [
			'foreignKey' => 'type'
		],
		'Resource' => [
			'foreignKey' => 'aco_foreign_key',
		],
		'Group' => [
			'foreignKey' => 'aro_foreign_key',
		]
	];


/**
 * Get the list of Resources that are accessible by a group.
 *
 * Be careful. This method will only return accurate results if called
 * by a group member. This is due to PermissionableBehavior.
 *
 * @param integer $groupId
 *   the group id
 *
 * @return array|null
 *   array that contains a list of resources, and for each:
 *   - Resource object
 *   - UserResourcePermission object
 *   - Permission object
 *
 */
	public function findAuthorizedResources($groupId = null, $permissionType = null) {

		// If instance id is not provided as parameter, we get it from the model.
		if (is_null($groupId)) {
			$groupId = $this->id;
		}

		// Get resources accessible by the group.
		// Start from model Resource.
		$resources = $this->Resource->find(
			'all',
			[
				'conditions' => [
					'Resource.deleted' => false,
				],
				'joins' => [
					[
						'table' => 'permissions',
						'alias' => 'GroupResourcePermission',
						'type' => 'inner',
						'conditions' => [
							"
							GroupResourcePermission.aco = 'Resource' 
							AND GroupResourcePermission.aro = 'Group'
							AND GroupResourcePermission.aro_foreign_key = '$groupId'
							AND GroupResourcePermission.aco_foreign_key = Resource.id
							"
							. ($permissionType != null ? ' AND GroupResourcePermission.type = ' . $permissionType : '')
						],
					]
				]
			]
		);

		return $resources;
	}

/**
 * Find resources whose sole owner is the given group.
 *
 * @param $groupId
 *   id of the group.
 *
 * @return mixed
 *   list of resources that are solely owned by the group.
 */
	public function findSoleOwnerResources($groupId) {
		// We unload permissionable as we need to be able to list all the resources here.
		$this->Resource->Behaviors->unload('Permissionable');

		$resourcesIsOwner = $this->findAuthorizedResources($groupId, PermissionType::OWNER);
		$resourcesIsOwnerIds = Hash::extract($resourcesIsOwner, '{n}.Resource.id');

		// For all the resources, find the ones with owners different than the given group.
		$resourcesWithOtherOwners = $this->Resource->find(
			'all',
			[
				'conditions' => [
					'Resource.deleted' => false,
					'Resource.id' => $resourcesIsOwnerIds,
					'GroupResourcePermission.aro_foreign_key <>' => $groupId
				],
				'joins' => [
					[
						'table' => 'permissions',
						'alias' => 'GroupResourcePermission',
						'type' => 'inner',
						'conditions' => [
							"
							GroupResourcePermission.aco = 'Resource'
							AND GroupResourcePermission.aco_foreign_key = Resource.id
							AND GroupResourcePermission.aro_foreign_key <> '$groupId'
							AND GroupResourcePermission.type = " . PermissionType::OWNER
						],
					]
				]
			]
		);

		// Load permissionable again.
		$this->Resource->Behaviors->load('Permissionable');

		// Make a difference between the 2 arrays.
		$resourcesWithOtherOwnersIds = Hash::extract($resourcesWithOtherOwners, '{n}.Resource.id');
		$soleOwnerIds = array_diff($resourcesIsOwnerIds, $resourcesWithOtherOwnersIds);

		// Build result.
		$resourcesIsSoleOwner = [];
		foreach($resourcesIsOwner as $key => $resourceIsOwner) {
			if (in_array($resourceIsOwner['Resource']['id'], $soleOwnerIds)) {
				$resourcesIsSoleOwner[] = $resourceIsOwner;
			}
		}

		return $resourcesIsSoleOwner;
	}

/**
 * Get the difference between the resources that are accessible by a group, but not accessible by a given user(s).
 *
 * Provides the list of resources accessible by the group, minus the ones that can already be accessed by a user
 * due to existing permissions.
 *
 * @param array $groupId
 *   array of group id
 * @param array $userIds
 *   a single uuid, or an array of user ids
 * @return array
 *  list of resources with only id populated, grouped by userId.
 */
	public function findUnauthorizedResourcesForUsers($groupId, $userIds) {

		if (!is_array($userIds)) {
			$userIds = [ $userIds ];
		}

		// Get the list of resources accessible by the group.
		$groupResources = $this->findAuthorizedResources($groupId);
		if (empty($groupResources)) {
			return [];
		}
		$groupResourceIds = Hash::extract($groupResources, '{n}.Resource.id');

		// Get the list of existing permissions for the given users and resources.
		$UserResourcePermission = Common::getModel('UserResourcePermission');
		$userResourcePermissions = $UserResourcePermission->find('all', [
			'conditions' => [
				'UserResourcePermission.user_id' => $userIds,
				'UserResourcePermission.resource_id' => $groupResourceIds,
			]
		]);


		// resources that will be returned, aggregated by user.
		$usersResources = [];

		// Build result.
		foreach ($userIds as $userId) {

			$usersResources[$userId] = [];
			foreach ($groupResourceIds as $groupResourceId) {

				// Look for existing permission.
				$permissionExists = false;
				foreach($userResourcePermissions as $userResourcePermission) {
					$permissionExists = $userResourcePermission['UserResourcePermission']['user_id'] == $userId
						&& $userResourcePermission['UserResourcePermission']['resource_id'] == $groupResourceId;
					if($permissionExists) { break; }
				}

				// If no permission exists, add the resource to the result.
				if ($permissionExists == false) {
					$usersResources[$userId][] = [
						'Resource' => [
							'id' => $groupResourceId,
						]
					];
				}
			}
		}

		return $usersResources;
	}
}