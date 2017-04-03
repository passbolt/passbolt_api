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
	public function findAuthorizedResources($groupId = null) {

		// If instance id is not provided as parameter, we get it from the model.
		if (is_null($groupId)) {
			$groupId = $this->id;
		}

		// Get Resource model.
		$Resource = Common::getModel("Resource");

		// Get resources accessible by the group.
		// Start from model Resource.
		$resources = $Resource->find(
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
						],
					]
				]
			]
		);

		return $resources;
	}
}