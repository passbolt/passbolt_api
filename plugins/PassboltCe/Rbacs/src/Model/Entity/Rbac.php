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
 * @property string $role_id
 * @property string $control_function
 * @property string $foreign_model
 * @property string $foreign_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property string|null $created_by
 * @property string|null $modified_by
 *
 * Associations
 * @property \App\Model\Entity\Role|null $role
 * @property \Passbolt\Log\Model\Entity\Action|null $action
 * @property \App\Model\Entity\User|null $modifier
 * @property \Passbolt\Rbacs\Model\Entity\UiAction|null $uiAction
 */
class Rbac extends Entity
{
    public const MAX_CONTROL_FUNCTION_NAME_LENGTH = 256;
    public const MAX_FOREIGN_MODEL_LENGTH = 36;

    public const CONTROL_FUNCTION_ALLOW = 'Allow';
    public const CONTROL_FUNCTION_DENY = 'Deny';
    public const CONTROL_FUNCTION_ALLOW_IF_GROUP_MANAGER_IN_ONE_GROUP = 'AllowIfGroupManagerInOneGroup';

    public const ALLOWED_CONTROL_FUNCTIONS = [
        self::CONTROL_FUNCTION_DENY,
        self::CONTROL_FUNCTION_ALLOW,
        self::CONTROL_FUNCTION_ALLOW_IF_GROUP_MANAGER_IN_ONE_GROUP,
    ];

    public const FOREIGN_MODEL_UI_ACTION = 'UiAction';
    public const FOREIGN_MODEL_ACTION = 'Action';

    public const ALLOWED_FOREIGN_MODELS = [
        self::FOREIGN_MODEL_UI_ACTION,
        //self::FOREIGN_MODEL_ACTION // TODO API endpoints level RBAC
    ];

    /**
     * List of property names that should **not** be included in JSON or Array
     * representations of this Entity.
     *
     * @var string[]
     */
    protected $_hidden = [
        '_joinData',
    ];

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'role_id' => false,
        'control_function' => false,
        'foreign_model' => false,
        'foreign_id' => false,
        'created' => false,
        'modified' => false,
        'created_by' => false,
        'modified_by' => false,
    ];
}
