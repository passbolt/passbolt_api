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
 * @since         5.18.0
 */
namespace Passbolt\TestData\Command\Base;

use App\Model\Entity\OrganizationSetting;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Utility\Hash;
use Exception;
use Passbolt\DirectorySync\Utility\DirectoryInterface;
use Passbolt\DirectorySync\Utility\DirectoryOrgSettings;
use Passbolt\TestData\Lib\DataCommand;

class LdapSettingsDataCommand extends DataCommand
{
    protected bool $_truncate = false;
    public string $entityName = 'OrganizationSettings';

    /**
     * Get the directory sync organization setting matching the ddev OpenLDAP server
     *
     * @return array
     */
    public function getData(): array
    {
        $propertyId = OrganizationSetting::UUID_NAMESPACE . DirectoryOrgSettings::ORG_SETTINGS_PROPERTY;

        $settings[] = [
            'id' => UuidFactory::uuid('organization_setting.id.directorySync'),
            'property' => DirectoryOrgSettings::ORG_SETTINGS_PROPERTY,
            'property_id' => UuidFactory::uuid($propertyId),
            'value' => json_encode($this->getSettings()),
            'created_by' => UuidFactory::uuid('user.id.admin'),
            'modified_by' => UuidFactory::uuid('user.id.admin'),
        ];

        return $settings;
    }

    /**
     * Remove any existing directory sync setting, as there is a single setting per organization.
     *
     * @return void
     */
    public function beforeDataExecute(): void
    {
        $orgSettingsTable = $this->fetchTable('OrganizationSettings');
        $orgSettingsTable->deleteAll(['property' => DirectoryOrgSettings::ORG_SETTINGS_PROPERTY]);
    }

    /**
     * @return array
     */
    private function getSettings(): array
    {
        return Hash::merge(DirectoryOrgSettings::getDefaultSettings(), [
            'fieldsMapping' => [
                DirectoryInterface::TYPE_OPENLDAP => [
                    'group' => [
                        'users' => 'uniqueMember',
                    ],
                ],
            ],
            'enabled' => true,
            'useEmailPrefixSuffix' => false,
            'defaultUser' => 'admin@passbolt.com',
            'defaultGroupAdminUser' => 'admin@passbolt.com',
            'enabledUsersOnly' => false,
            'fieldFallbacks' => [
                DirectoryInterface::TYPE_AD => ['username' => ''],
            ],
            'deleteUserBehavior' => 'delete',
            'ldap' => [
                'domains' => [
                    'org_domain' => [
                        'ldap_type' => DirectoryInterface::TYPE_OPENLDAP,
                        'domain_name' => 'example.org',
                        'username' => 'cn=admin,dc=example,dc=org',
                        'password' => $this->encrypt('admin'),
                        'base_dn' => 'dc=example,dc=org',
                        'hosts' => ['openldap'],
                        'port' => 389,
                        'use_ssl' => 0,
                        'use_tls' => 0,
                        'use_sasl' => 0,
                    ],
                ],
            ],
        ]);
    }

    /**
     * Encrypt for the server OpenPGP key, as DirectoryOrgSettings decrypts the password on read.
     *
     * @param string $data The message to encrypt
     * @return string
     */
    private function encrypt(string $data): string
    {
        $fingerprint = Configure::read('passbolt.gpg.serverKey.fingerprint');
        $passphrase = Configure::read('passbolt.gpg.serverKey.passphrase');
        $gpg = OpenPGPBackendFactory::get();

        try {
            $gpg->setSignKeyFromFingerprint($fingerprint, $passphrase);
            $gpg->setEncryptKeyFromFingerprint($fingerprint);
        } catch (Exception $exception) {
            $gpg->importServerKeyInKeyring();
            $gpg->setSignKeyFromFingerprint($fingerprint, $passphrase);
            $gpg->setEncryptKeyFromFingerprint($fingerprint);
        }

        return $gpg->encrypt($data, true);
    }
}
