<?php
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
 * @since         2.0.0
 */
namespace App\Model\Entity;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * User Entity
 *
 * @property string $id
 * @property string $role_id
 * @property string $username
 * @property bool $active
 * @property bool $deleted
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Role $role
 * @property \App\Model\Entity\FileStorage[] $file_storage
 * @property \App\Model\Entity\Gpgkey[] $gpgkeys
 * @property \App\Model\Entity\Profile[] $profiles
 * @property \App\Model\Entity\GroupUser[] $groups_users
 */
class User extends Entity
{
    /**
     * last_logged_in virtual field.
     * @var array
     */
    protected $_virtual = ['last_logged_in'];

    /**
     * Placeholder name for last_logged_in.
     */
    const LAST_LOGGED_IN_PLACEHOLDER = '__placeholder_last_logged_in__';

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'id' => false,
        'username' => false,
        'active' => false,
        'deleted' => false,
        'role_id' => false,

        // associated data
        'profile' => false
    ];

    /**
     * Url virtual field implementation.
     * @return string
     */
    protected function _getLastLoggedIn()
    {
        $fieldExist = isset($this->{self::LAST_LOGGED_IN_PLACEHOLDER});
        if ($fieldExist) {
            $this->__unset(self::LAST_LOGGED_IN_PLACEHOLDER);
            if ($this->active == true) {
                $token = $this->_getAuthenticationTokensQuery();
                if ($token) {
                    return $token->modified;
                }
            }
        }

        return "";
    }

    /**
     * Get a query that returns used authentication tokens for a given user.
     * @return EntityInterface
     */
    protected function _getAuthenticationTokensQuery()
    {
        $AuthenticationTokens = TableRegistry::getTableLocator()->get('AuthenticationTokens');
        $tokenQuery = $AuthenticationTokens
            ->find()
            ->select([
                'modified'
            ])
            ->where([
                'user_id' => $this->id,
                'active' => 0,
                'type' => AuthenticationToken::TYPE_LOGIN
            ])
            ->order('modified DESC')
            ->first();

        return $tokenQuery;
    }
}
