<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.2.0
 */
namespace Passbolt\DirectorySync\Utility;

interface DirectoryInterface
{
    /**
     * Directory Type constants
     */
    public const TYPE_AD = 'ad';
    public const TYPE_OPENLDAP = 'openldap';
    public const TYPE_FREEIPA = 'freeipa';
    public const TYPE_NAME_OPENLDAP = 'OpenLDAP';
    public const TYPE_NAME_AD = 'ActiveDirectory';
    public const TYPE_NAME_FREEIPA = 'FreeIPA';

    /**
     * Entry type constants
     */
    public const ENTRY_TYPE_GROUP = 'group';
    public const ENTRY_TYPE_USER = 'user';

    /**
     * Filter constants
     */
    public const AD_ENABLED_USERS_FILTER = '(!(userAccountControl:1.2.840.113556.1.4.803:=2))';

    /**
     * SASL Mechanisms constants
     */
    public const SASL_MECH_GSSAPI = 'GSSAPI';

    /**
     * Get users.
     *
     * @return mixed
     */
    public function getUsers();

    /**
     * Get groups.
     *
     * @return mixed
     */
    public function getGroups();

    /**
     * Get filtered directory results.
     *
     * @return mixed
     */
    public function getFilteredDirectoryResults();

    /**
     * Sets users.
     *
     * @param array $users users
     * @return $this
     */
    public function setUsers($users);

    /**
     * Sets groups.
     *
     * @param array $groups groups
     * @return $this
     */
    public function setGroups($groups);
}
