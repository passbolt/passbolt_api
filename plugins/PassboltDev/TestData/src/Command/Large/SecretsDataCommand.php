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
 * @since         2.0.0
 */
namespace Passbolt\TestData\Command\Large;

use App\Model\Entity\Role;
use App\Utility\UuidFactory;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Core\Configure;
use Cake\ORM\Entity;
use Passbolt\TestData\Lib\DataCommand;
use Passbolt\TestData\Service\GetGpgkeyPathService;

class SecretsDataCommand extends DataCommand
{
    public string $entityName = 'Secrets';
    protected array $gpgkeys = [];

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $gpgkeysTable = $this->fetchTable('Gpgkeys');

        // Retrieve the key info.
        // As a default key can be shared among user, the encryption will require the key fingerprint.
        // As the key meta data are already stored in db, get the meta data from the db and avoid performance issue
        // by avoiding any gpg extra parsing.
        $gpgkeys = $gpgkeysTable->find()->all();
        foreach ($gpgkeys as $gpgkey) {
            $this->gpgkeys[$gpgkey->user_id] = $gpgkey->fingerprint;
        }

        return parent::execute($args, $io);
    }

    /**
     * Get encrypted secrets
     *
     * @return array
     */
    public function getData(): array
    {
        if (Configure::read('passbolt.gpg.putenv')) {
            putenv('GNUPGHOME=' . Configure::read('passbolt.gpg.keyring'));
        }

        $secrets = [];

        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = $this->fetchTable('Users');
        /** @var \App\Model\Table\ResourcesTable $resourcesTable */
        $resourcesTable = $this->fetchTable('Resources');

        $users = $usersTable->findIndex(Role::USER);
        foreach ($users as $user) {
            $resources = $resourcesTable->findIndex($user->get('id'));
            foreach ($resources as $resource) {
                /** @var \Cake\ORM\Entity $user */
                $armoredPassword = $this->_encrypt('dummy password', $user);
                $secrets[] = [
                    'id' => UuidFactory::uuid("secret.id.{$resource->get('id')}-{$user->get('id')}"),
                    'user_id' => $user->get('id'),
                    'resource_id' => $resource->get('id'),
                    'data' => $armoredPassword,
                    'created_by' => $user->get('id'),
                    'created' => date('Y-m-d H:i:s'),
                    'modified' => date('Y-m-d H:i:s'),
                ];
            }
        }

        return $secrets;
    }

    /**
     * Encrypt passwords
     *
     * @param string $text password
     * @param \Cake\ORM\Entity $user User
     * @return string
     */
    protected function _encrypt(string $text, Entity $user): string
    {
        static $keyImported = [];
        static $encrypted = [];
        $keyFingerprint = $this->gpgkeys[$user->get('id')];

        // Import the user public key.
        if (!isset($keyImported[$keyFingerprint])) {
            // Retrieve the user key file.
            $gpgkeyPath = (new GetGpgkeyPathService())->get($user->get('id'));

            exec('gpg --import ' . $gpgkeyPath . ' > /dev/null 2>&1');
            $keyImported[$keyFingerprint] = true;
        }

        // Encrypt the text.
        if (!isset($encrypted[$keyFingerprint][$text])) {
            $command = 'echo -n ' . escapeshellarg($text) . ' | gpg --encrypt -r ' . $keyFingerprint . ' -a --trust-model always'; //phpcs:ignore
            exec($command, $output);

            // Return the armored message.
            $encrypted[$keyFingerprint][$text] = implode("\n", $output);
        }

        return $encrypted[$keyFingerprint][$text];
    }
}
