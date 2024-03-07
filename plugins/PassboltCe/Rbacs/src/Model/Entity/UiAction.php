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
namespace Passbolt\Rbacs\Model\Entity;

use Cake\ORM\Entity;

/**
 * Rbac Entity
 * Base props
 *
 * @property string $id
 * @property string $name
 */
class UiAction extends Entity
{
    public const NAME_MAX_LENGTH = 255;

    /**
     * Available UI actions
     */
    public const NAME_RESOURCES_IMPORT = 'Resources.import';
    public const NAME_RESOURCES_EXPORT = 'Resources.export';
    public const NAME_SECRETS_PREVIEW = 'Secrets.preview';
    public const NAME_SECRETS_COPY = 'Secrets.copy';
    public const NAME_RESOURCES_TOGGLE_DESCRIPTION = 'Resources.toggleDescription';
    public const NAME_RESOURCES_SEE_COMMENTS = 'Resources.seeComments';
    public const NAME_RESOURCES_SEE_ACTIVITIES = 'Resources.seeActivities';
    public const NAME_FOLDERS_USE = 'Folders.use';
    public const NAME_RESOURCES_FILTER_BY_GROUPS = 'Resources.filterByGroups';
    public const NAME_TAGS_USE = 'Tags.use';
    public const NAME_SHARE_VIEW_LIST = 'Share.viewList';
    public const NAME_SHARE_VIEW_USERS_IN_AUTOCOMPLETE = 'Share.viewUsersInAutocomplete';
    public const NAME_SHARE_VIEW_GROUPS_IN_AUTOCOMPLETE = 'Share.viewGroupsInAutocomplete';
    public const NAME_IN_FORM_MENU_USE = 'InFormMenu.use';
    public const NAME_RESOURCES_EDIT_PASSWORD_GENERATOR_SETTINGS = 'Resources.editPasswordGeneratorSettings';
    public const NAME_USERS_VIEW_WORKSPACE = 'Users.viewWorkspace';
    public const NAME_USERS_VIEW_GROUPS = 'Users.viewGroups';
    public const NAME_MOBILE_TRANSFER = 'Mobile.transfer';
    public const NAME_DESKTOP_TRANSFER = 'Desktop.transfer';
    public const NAME_FOLDERS_SHARE = 'Folders.share';

    /**
     * Mapping for name and control function.
     *
     * @var array
     */
    public const CONTROL_FUNCTION_MAPPING = [
        self::NAME_RESOURCES_IMPORT => [
            Rbac::CONTROL_FUNCTION_ALLOW,
            Rbac::CONTROL_FUNCTION_DENY,
        ],
        self::NAME_RESOURCES_EXPORT => [
            Rbac::CONTROL_FUNCTION_ALLOW,
            Rbac::CONTROL_FUNCTION_DENY,
        ],
        self::NAME_SECRETS_PREVIEW => [
            Rbac::CONTROL_FUNCTION_ALLOW,
            Rbac::CONTROL_FUNCTION_DENY,
        ],
        self::NAME_SECRETS_COPY => [
            Rbac::CONTROL_FUNCTION_ALLOW,
            Rbac::CONTROL_FUNCTION_DENY,
        ],
        self::NAME_RESOURCES_TOGGLE_DESCRIPTION => [
            Rbac::CONTROL_FUNCTION_ALLOW,
            Rbac::CONTROL_FUNCTION_DENY,
        ],
        self::NAME_RESOURCES_SEE_COMMENTS => [
            Rbac::CONTROL_FUNCTION_ALLOW,
            Rbac::CONTROL_FUNCTION_DENY,
        ],
        self::NAME_RESOURCES_SEE_ACTIVITIES => [
            Rbac::CONTROL_FUNCTION_ALLOW,
            Rbac::CONTROL_FUNCTION_DENY,
        ],
        self::NAME_FOLDERS_USE => [
            Rbac::CONTROL_FUNCTION_ALLOW,
            Rbac::CONTROL_FUNCTION_DENY,
        ],
        self::NAME_RESOURCES_FILTER_BY_GROUPS => [
            Rbac::CONTROL_FUNCTION_ALLOW,
            Rbac::CONTROL_FUNCTION_DENY,
        ],
        self::NAME_TAGS_USE => [
            Rbac::CONTROL_FUNCTION_ALLOW,
            Rbac::CONTROL_FUNCTION_DENY,
        ],
        self::NAME_SHARE_VIEW_LIST => [
            Rbac::CONTROL_FUNCTION_ALLOW,
            Rbac::CONTROL_FUNCTION_DENY,
        ],
        self::NAME_SHARE_VIEW_USERS_IN_AUTOCOMPLETE => [
            Rbac::CONTROL_FUNCTION_ALLOW,
            Rbac::CONTROL_FUNCTION_DENY,
        ],
        self::NAME_SHARE_VIEW_GROUPS_IN_AUTOCOMPLETE => [
            Rbac::CONTROL_FUNCTION_ALLOW,
            Rbac::CONTROL_FUNCTION_DENY,
        ],
        self::NAME_IN_FORM_MENU_USE => [
            Rbac::CONTROL_FUNCTION_ALLOW,
            Rbac::CONTROL_FUNCTION_DENY,
        ],
        self::NAME_RESOURCES_EDIT_PASSWORD_GENERATOR_SETTINGS => [
            Rbac::CONTROL_FUNCTION_ALLOW,
            Rbac::CONTROL_FUNCTION_DENY,
        ],
        self::NAME_USERS_VIEW_WORKSPACE => [
            Rbac::CONTROL_FUNCTION_ALLOW,
            Rbac::CONTROL_FUNCTION_DENY,
            Rbac::CONTROL_FUNCTION_ALLOW_IF_GROUP_MANAGER_IN_ONE_GROUP,
        ],
        self::NAME_USERS_VIEW_GROUPS => [
            Rbac::CONTROL_FUNCTION_ALLOW,
            Rbac::CONTROL_FUNCTION_DENY,
        ],
        self::NAME_MOBILE_TRANSFER => [
            Rbac::CONTROL_FUNCTION_ALLOW,
            Rbac::CONTROL_FUNCTION_DENY,
        ],
        self::NAME_DESKTOP_TRANSFER => [
            Rbac::CONTROL_FUNCTION_ALLOW,
            Rbac::CONTROL_FUNCTION_DENY,
        ],
        self::NAME_FOLDERS_SHARE => [
            Rbac::CONTROL_FUNCTION_ALLOW,
            Rbac::CONTROL_FUNCTION_DENY,
        ],
    ];

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to false, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'name' => false,
    ];
}
