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

namespace Passbolt\Folders\Model\Traits\Folders;

use App\Model\Table\AvatarsTable;
use App\Model\Table\PermissionsTable;
use App\Model\Traits\Query\CaseInsensitiveSearchQueryTrait;
use Cake\Database\Expression\IdentifierExpression;
use Cake\Database\Expression\QueryExpression;
use Cake\ORM\Query;
use Cake\Validation\Validation;
use InvalidArgumentException;
use Passbolt\Folders\Model\Behavior\FolderizableBehavior;
use Passbolt\Folders\Model\Entity\Folder;
use Passbolt\Metadata\Model\Entity\MetadataKey;

/**
 * Trait FoldersFindersTrait
 *
 * @package Passbolt\Folders\Model\Traits\Folders
 * @mixin \Passbolt\Folders\Model\Behavior\FolderizableBehavior
 */
trait FoldersFindersTrait
{
    use CaseInsensitiveSearchQueryTrait;

    /**
     * Build the query that fetches data for folders index
     *
     * @psalm-suppress UndefinedMethod
     * @param string $userId The user to get the folders for
     * @param array|null $options options
     * @return \Cake\ORM\Query
     * @throws \InvalidArgumentException if the userId parameter is not a valid uuid.
     */
    public function findIndex(string $userId, ?array $options = [])
    {
        if (!Validation::uuid($userId)) {
            throw new InvalidArgumentException('The user identifier should be a valid UUID.');
        }

        /** @phpstan-ignore-next-line */
        $query = $this->find();

        $query->find(FolderizableBehavior::FINDER_NAME, ['user_id' => $userId]);

        if (isset($options['filter']['has-id'])) {
            $this->filterByIds($query, $options['filter']['has-id']);
        }

        if (isset($options['filter']['search'])) {
            $this->filterQueryBySearch($query, $options['filter']['search'][0]);
        }

        if (isset($options['filter']['has-parent'])) {
            $this->filterQueryByParentIds($query, $options['filter']['has-parent']);
        }

        // If contains the user permission, retrieve the highest permission the user has for each folder.
        // In the meantime filter only the folder the user has access, the permissions table will be joined
        // to the folders table with an INNER join, see the hasOne definition.
        if (isset($options['contain']['permission'])) {
            $query->contain('Permission', function (Query $q) use ($userId) {
                $acoForeignKey = new IdentifierExpression('Folders.id');
                $permissionIdSubQuery = $this->Permissions
                    ->findHighestByAcoAndAro(PermissionsTable::FOLDER_ACO, $acoForeignKey, $userId)
                    ->select(['Permissions.id']);

                return $q->where(['Permission.id' => $permissionIdSubQuery]);
            });
        } else {
            // Filter on folders the user has access
            $query = $this->_filterQueryByPermissions($query, $userId);
        }

        // If contains children_folders.
        if (isset($options['contain']['children_folders'])) {
            $query->contain('ChildrenFolders', function (Query $q) use ($userId) {
                return $q->where(['FoldersRelations.user_id' => $userId])
                    ->find(FolderizableBehavior::FINDER_NAME, user_id: $userId);
            });
        }

        // If contains children_resources.
        if (isset($options['contain']['children_resources'])) {
            $query->contain('ChildrenResources', function (Query $q) use ($userId) {
                return $q->where(['FoldersRelations.user_id' => $userId])
                    ->find(FolderizableBehavior::FINDER_NAME, user_id: $userId);
            });
        }

        // If contains creator.
        if (isset($options['contain']['creator'])) {
            $query->contain('Creator');
        }

        // If contains create profile.
        if (isset($options['contain']['creator.profile'])) {
            $query->contain([
                'Creator' => [
                    'Profiles' => AvatarsTable::addContainAvatar(),
                ],
            ]);
        }

        // If contains modifier.
        if (isset($options['contain']['modifier'])) {
            $query->contain('Modifier');
        }

        // If contains create profile.
        if (isset($options['contain']['modifier.profile'])) {
            $query->contain([
                'Modifier' => [
                    'Profiles' => AvatarsTable::addContainAvatar(),
                ],
            ]);
        }

        // Retrieve the permission and the details of a user attach to it if any
        if (isset($options['contain']['permissions'])) {
            $query->contain(['Permissions']);
        }

        // Retrieve the permission and the details of a user attach to it if any
        if (isset($options['contain']['permissions.user.profile'])) {
            $query->contain([
                'Permissions' => [
                    'Users' => [
                        'Profiles' => AvatarsTable::addContainAvatar(),
                    ],
                ],
            ]);
        }

        // Retrieve the permission and the details of a group attach to it if any
        if (isset($options['contain']['permissions.group'])) {
            $query->contain('Permissions.Groups');
        }

        return $query;
    }

    /**
     * Returns all folders with expired metadata key.
     *
     * @return \Cake\ORM\Query
     */
    public function findMetadataRotateKeyIndex(): Query
    {
        $query = $this->find();

        return $query
            ->where([
                'Folders.metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                $query->newExpr()->isNotNull('Folders.metadata'),
                $query->newExpr()->isNotNull('Folders.metadata_key_id'),
            ])
            ->innerJoin(['MetadataKeys' => 'metadata_keys'], [
                'MetadataKeys.id' => new IdentifierExpression('Folders.metadata_key_id'),
                $query->newExpr()->isNotNull('MetadataKeys.expired'),
            ])
            ->disableHydration();
    }

    /**
     * Build the query that fetches data for folders view
     *
     * @param string $userId The user to get the folders for
     * @param string $folderId The folder to retrieve
     * @param array|null $options options
     * @return \Cake\ORM\Query
     */
    public function findView(string $userId, string $folderId, ?array $options = []): Query
    {
        return $this->findIndex($userId, $options)
            ->where(['Folders.id' => $folderId]);
    }

    /**
     * Filter a folders query to return only folders the user has access to.
     *
     * @param \Cake\ORM\Query $query The query to filter.
     * @param string $userId The user to check the permissions for.
     * @return \Cake\ORM\Query
     */
    private function _filterQueryByPermissions(Query $query, string $userId)
    {
        $subQueryOptions = [
            'checkGroupsUsers' => true,
        ];
        $folderPermissions = $this->Permissions
            ->findAllByAro(PermissionsTable::FOLDER_ACO, $userId, $subQueryOptions)
            ->select(['Permissions.id'])
            ->where(['Permissions.aco_foreign_key' => new IdentifierExpression('Folders.id')])
            ->limit(1);

        return $query->innerJoin(['FolderPermissions' => 'permissions'], [
            'FolderPermissions.id' => $folderPermissions,
        ]);
    }

    /**
     * Filter a query to retrieve folders on their ids with the given list of ids
     *
     * @param \Cake\ORM\Query $query Query to filter on
     * @param array $folderIds array of folders ids
     * @return \Cake\ORM\Query|\Passbolt\Folders\Model\Entity\Folder[]
     */
    public function filterByIds(Query $query, array $folderIds)
    {
        return $query->where(['Folders.id IN' => $folderIds]);
    }

    /**
     * Filter a Folders query by search.
     * Search on the name field.
     *
     * By instance :
     * $query = $Folders->find();
     * $Groups->_filterQueryBySearch($query, 'creative');
     *
     * Should filter all the folders with a name containing creative.
     * Search should be case-insensitive
     *
     * @param \Cake\ORM\Query $query Query to filter
     * @param string $name Name to filter
     * @return \Cake\ORM\Query
     */
    public function filterQueryBySearch(Query $query, string $name): Query
    {
        return $this->searchCaseInsensitiveOnField($query, 'Folders.name', $name);
    }

    /**
     * Filter a query by parents ids.
     *
     * @param \Cake\ORM\Query $query Query to filter on
     * @param array $parentIds Array of parent ids
     * @return \Cake\ORM\Query
     */
    public function filterQueryByParentIds(Query $query, array $parentIds): Query
    {
        if (empty($parentIds)) {
            return $query;
        }

        $includeRoot = false;
        $parentIds = array_filter($parentIds, function ($value) use (&$includeRoot) {
            if ($value == Folder::ROOT_ID) {
                $includeRoot = true;

                return false;
            }

            return true;
        });

        return $query->innerJoinWith('ChildrenFolders', function (Query $q) use ($parentIds, $includeRoot) {
            $conditions = [];
            if (!empty($parentIds)) {
                $conditions[] = $q->expr()->in('FoldersRelations.folder_parent_id', $parentIds);
            }
            if ($includeRoot === true) {
                $conditions[] = $q->expr()->isNull('FoldersRelations.folder_parent_id');
            }

            return $q->where(['OR' => $conditions]);
        });
    }

    /**
     * Returns all resources in v4 format that need to be upgraded.
     *
     * @param array $options query options
     * @return \Cake\ORM\Query
     */
    public function findMetadataUpgradeIndex(array $options): Query
    {
        $query = $this->find('v4')->disableHydration();

        $containPermissions = (bool)($options['contain']['permissions'] ?? false);
        if ($containPermissions) {
            $query->contain('Permissions');
        }

        if (!isset($options['filter']['is-shared'])) {
            return $query;
        }

        $isShared = $options['filter']['is-shared'];
        $groupPermissionsCount = $this->Permissions->find()
            ->select(['permissions_on_groups' => 'COUNT(*)'])
            ->where([
                'Permissions.aco_foreign_key' => $query->identifier('Folders.id'),
                'Permissions.aco' => PermissionsTable::FOLDER_ACO,
                'Permissions.aro' => PermissionsTable::GROUP_ARO,
            ]);
        $userPermissionsCount = $this->Permissions->find()
            ->select(['permissions_on_users' => 'COUNT(*)'])
            ->where([
                'Permissions.aco_foreign_key' => $query->identifier('Folders.id'),
                'Permissions.aco' => PermissionsTable::FOLDER_ACO,
                'Permissions.aro' => PermissionsTable::USER_ARO,
            ]);
        if ($isShared === true) {
            // Is shared if at least one permission is a group permission
            // OR if at least two permissions are user permissions
            $query->where(function (QueryExpression $exp) use ($groupPermissionsCount, $userPermissionsCount) {
                return $exp->or(function (QueryExpression $or) use ($groupPermissionsCount, $userPermissionsCount) {
                    return $or->gte($userPermissionsCount, 2)->gte($groupPermissionsCount, 1);
                });
            });
        } elseif ($isShared === false) {
            // Is not shared if no permission is a group permission
            // AND the only permission is a user permission
            $query->where(function (QueryExpression $exp) use ($groupPermissionsCount, $userPermissionsCount) {
                return $exp->eq($groupPermissionsCount, 0)->eq($userPermissionsCount, 1);
            });
        }

        return $query;
    }

    /**
     * @param \Cake\ORM\Query $query Query
     * @return \Cake\ORM\Query
     */
    public function findV4(Query $query): Query
    {
        return $query->where([
            $query->newExpr()->isNull('Folders.metadata'),
        ]);
    }
}
