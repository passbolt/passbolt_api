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
 * @since         4.1.0
 */

namespace Passbolt\Rbacs\Service\UiActions;

use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\Rbacs\Model\Entity\UiAction;
use Passbolt\Rbacs\Model\Table\UiActionsTable;

class UiActionsInsertDefaultsService
{
    /**
     * @var \Passbolt\Rbacs\Model\Table\UiActionsTable $uiActionsTable
     */
    private UiActionsTable $uiActionsTable;

    /**
     * Default UI Actions
     */
    public const DEFAULT_UI_ACTIONS = [
        UiAction::NAME_RESOURCES_IMPORT,
        UiAction::NAME_RESOURCES_EXPORT,
        UiAction::NAME_SECRETS_PREVIEW,
        UiAction::NAME_SECRETS_COPY,
        UiAction::NAME_RESOURCES_TOGGLE_DESCRIPTION,
        UiAction::NAME_RESOURCES_SEE_COMMENTS,
        UiAction::NAME_RESOURCES_SEE_ACTIVITIES,
        UiAction::NAME_FOLDERS_USE,
        UiAction::NAME_RESOURCES_FILTER_BY_GROUPS,
        UiAction::NAME_TAGS_USE,
        UiAction::NAME_SHARE_VIEW_LIST,
        UiAction::NAME_SHARE_VIEW_USERS_IN_AUTOCOMPLETE,
        UiAction::NAME_SHARE_VIEW_GROUPS_IN_AUTOCOMPLETE,
        UiAction::NAME_IN_FORM_MENU_USE,
        UiAction::NAME_RESOURCES_EDIT_PASSWORD_GENERATOR_SETTINGS,
        UiAction::NAME_USERS_VIEW_WORKSPACE,
        UiAction::NAME_USERS_VIEW_GROUPS,
        UiAction::NAME_MOBILE_TRANSFER,
        UiAction::NAME_DESKTOP_TRANSFER,
        UiAction::NAME_FOLDERS_SHARE,
    ];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->uiActionsTable = TableRegistry::getTableLocator()->get('Passbolt/Rbacs.UiActions');
    }

    /**
     * @return array array of UI Actions entities
     * @throws \Cake\ORM\Exception\PersistenceFailedException If an entity couldn't be saved.
     */
    public function insertDefaultsIfNotExist(): array
    {
        $diff = $this->getDiffDefaultAndDB();
        if (count($diff) === 0) {
            return [];
        }

        $entities = [];
        foreach ($diff as $name) {
            $entities[] = compact('name');
        }

        $newEntities = $this->uiActionsTable->newEntities($entities, ['accessibleFields' => ['name' => true]]);

        $this->uiActionsTable->saveManyOrFail($newEntities);

        return $diff;
    }

    /**
     * @return array
     */
    public function getDiffDefaultAndDB(): array
    {
        $existing = $this->uiActionsTable->find()
            ->select('name')
            ->where(['name IN' => self::DEFAULT_UI_ACTIONS])
            ->all()
            ->toArray();

        $existing = Hash::sort(Hash::extract($existing, '{n}.name'), '{n}');
        $default = Hash::sort(self::DEFAULT_UI_ACTIONS, '{n}');

        if (count($existing) !== 0) {
            $diff = array_values(array_diff($default, $existing));
        } else {
            $diff = $default;
        }

        return $diff;
    }
}
