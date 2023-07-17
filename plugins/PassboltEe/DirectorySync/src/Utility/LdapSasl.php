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
 * @since         4.0.0
 */
namespace Passbolt\DirectorySync\Utility;

use LdapRecord\Ldap;

class LdapSasl extends Ldap
{
    protected array $saslOptions = [
        'mech' => DirectoryInterface::SASL_MECH_GSSAPI,
        'realm' => '',
        'authc_id' => '',
        'authz_id' => '',
        'props' => '',
    ];

    /**
     * Constructor
     *
     * SASL options:
     *  - mech: Mechanism (Defaults: null)
     *  - realm: Realm (Defaults: null)
     *  - authc_id: Verification Identity (Defaults: null)
     *  - authz_id: Authorization Identity (Defaults: null)
     *  - props: Options for Authorization Identity (Defaults: null)
     *
     * @see https://php.net/manual/en/function.ldap-bind.php
     * @see https://php.net/manual/en/function.ldap-sasl-bind.php
     * @see https://www.iana.org/assignments/sasl-mechanisms/sasl-mechanisms.xhtml
     * @param array $saslOptions SASL Options
     */
    public function __construct(array $saslOptions = [])
    {
        $this->saslOptions = array_merge($this->saslOptions, $saslOptions);
    }

    /**
     * @inheritDoc
     */
    public function bind($username, $password)
    {
        //SASL only works with LDAP v3
        $this->setOption(LDAP_OPT_PROTOCOL_VERSION, 3);

        return $this->bound = $this->executeFailableOperation(function () use ($username, $password) {
            return ldap_sasl_bind(
                $this->connection,
                $username,
                $password ? html_entity_decode($password) : '',
                $this->saslOptions['mech'],
                $this->saslOptions['realm'],
                $this->saslOptions['authc_id'],
                $this->saslOptions['authz_id'],
                $this->saslOptions['props']
            );
        });
    }
}
