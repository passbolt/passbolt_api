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
 * @since         4.11.0
 */
namespace Passbolt\Metadata\Command;

use App\Command\PassboltCommand;
use App\Model\Entity\User;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Database\Expression\IdentifierExpression;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\Metadata\Model\Entity\MetadataPrivateKey;
use Passbolt\Metadata\Service\MetadataKeyShareDefaultService;
use Passbolt\Metadata\Service\MetadataKeysSettingsGetService;

class ShareMetadataKeyCommand extends PassboltCommand
{
    /**
     * @inheritDoc
     */
    public static function getCommandDescription(): string
    {
        return __('Share metadata keys with users who are missing them.');
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);

        $metadataKeys = $this->getMetadataKeys();
        if (empty($metadataKeys)) {
            $this->success(__('Nothing to share. No metadata key set. Create a metadata key first.'), $io);

            return $this->successCode();
        }

        $metadataKeySettings = MetadataKeysSettingsGetService::getSettings();
        if ($metadataKeySettings->isKeyShareZeroKnowledge()) {
            $this->error(__('Cannot share metadata key when in the zero knowledge key share mode.'), $io);

            return $this->errorCode();
        }

        /** @var \Cake\Collection\CollectionInterface $usersResultSet */
        $usersResultSet = $this->getUsersWithMissingMetadataKey()->all();
        if ($usersResultSet->isEmpty()) {
            $this->success(__('No users found.'), $io);

            return $this->successCode();
        }

        $metadataKeys = Hash::combine($metadataKeys, '{n}.id', '{n}');
        /** @var \App\Model\Table\UsersTable $Users */
        $Users = TableRegistry::getTableLocator()->get('Users');
        $admin = $Users->findFirstAdminOrThrowNoAdminInDbException();

        // Using chunk here to be memory efficient with large data sets (i.e. thousands of users)
        $error = false;
        $usersResultSet
            ->chunk(50)
            ->each(function ($usersBatch) use ($admin, $metadataKeys, $io, &$error) {
                $this->shareMetadataKeyWithUsers($admin, $usersBatch, $metadataKeys, $io, $error);
            });

        return $error ? $this->errorCode() : $this->successCode();
    }

    /**
     * Returns all not deleted metadata keys.
     *
     * @return \Passbolt\Metadata\Model\Entity\MetadataKey[]
     */
    private function getMetadataKeys(): array
    {
        $metadataKeysQuery = TableRegistry::getTableLocator()->get('Passbolt/Metadata.MetadataKeys')->find();

        return $metadataKeysQuery
            ->contain(['MetadataPrivateKeys' => function (Query $query) {
                // get server key data along with the metadata key
                return $query->where([$query->newExpr()->isNull('user_id')]);
            }])
            ->where([$metadataKeysQuery->newExpr()->isNull('deleted')])
            ->toArray();
    }

    /**
     * Returns all users - missing metadata keys combination.
     * I.e. for 10 users missing 2 metadata keys each, the query will return 20 results.
     *
     * @return \Cake\ORM\Query
     */
    private function getUsersWithMissingMetadataKey(): Query
    {
        $usersQuery = TableRegistry::getTableLocator()->get('Users')->find();

        return $usersQuery
            ->select([
                'Users.id',
                'Users.username',
                'MissingMetadataKeys.id',
                'MissingMetadataKeys.fingerprint',
                'Gpgkeys.id',
                'Gpgkeys.armored_key',
                'Gpgkeys.key_id',
                'Gpgkeys.fingerprint',
            ])
            ->find('activeNotDeleted')
            ->contain('Gpgkeys')
            ->innerJoin(['MissingMetadataKeys' => 'metadata_keys'], [
                $usersQuery->newExpr()->isNull('MissingMetadataKeys.deleted'),
            ])
            ->leftJoin(['MetadataPrivateKeys' => 'metadata_private_keys'], [
                'MetadataPrivateKeys.user_id' => new IdentifierExpression('Users.id'),
                'MetadataPrivateKeys.metadata_key_id' => new IdentifierExpression('MissingMetadataKeys.id'),
                $usersQuery->newExpr()->isNotNull('MetadataPrivateKeys.user_id'),
            ])
            ->where([$usersQuery->newExpr()->isNull('MetadataPrivateKeys.metadata_key_id')]);
    }

    /**
     * @param \App\Model\Entity\User $admin admin populating the created_by and modified_by fields.
     * @param \App\Model\Entity\User[] $users Users to share metadata key with.
     * @param array $metadataKeys Existing metadata keys with associated private key.
     * @param \Cake\Console\ConsoleIo $io I/O object.
     * @param bool $error Set it to true in case of any error occurrence.
     * @return void
     */
    private function shareMetadataKeyWithUsers(
        User $admin,
        array $users,
        array $metadataKeys,
        ConsoleIo $io,
        bool &$error
    ): void {
        $metadataKeyShareService = new MetadataKeyShareDefaultService();

        foreach ($users as $user) {
            $missingMetadataKeyId = $user['MissingMetadataKeys']['id'];

            try {
                $metadataPrivateKey = $this->findMetadataPrivateKey($missingMetadataKeyId, $metadataKeys);
            } catch (NotFoundException $e) {
                $msg = __('The metadata key {0} could not be shared with user {1}.', $missingMetadataKeyId, $user->username); // phpcs:ignore
                $msg .= ' ' . $e->getMessage();
                $this->error($msg, $io);

                $error = true;

                continue;
            }

            try {
                $metadataKeyShareService->shareMetadataKeyWithUser($user, $metadataPrivateKey, $admin->id);
            } catch (\Exception $e) {
                $msg = __('The metadata key {0} could not be shared with user {1}.', $missingMetadataKeyId, $user->username); // phpcs:ignore
                $msg .= ' ' . $e->getMessage();
                $this->error($msg, $io);

                $error = true;

                continue;
            }

            $this->success(
                __('The metadata key {0} was shared with user {1}.', $missingMetadataKeyId, $user->username),
                $io
            );
        }
    }

    /**
     * @param string $missingMetadataKeyId Metadata key id to find.
     * @param array $metadataKeys Metadata keys.
     * @return \Passbolt\Metadata\Model\Entity\MetadataPrivateKey
     */
    private function findMetadataPrivateKey(string $missingMetadataKeyId, array $metadataKeys): MetadataPrivateKey
    {
        if (empty($metadataKeys[$missingMetadataKeyId]['metadata_private_keys'])) {
            throw new NotFoundException(__('Missing metadata private key.', $missingMetadataKeyId));
        }

        return $metadataKeys[$missingMetadataKeyId]['metadata_private_keys'][0];
    }
}
