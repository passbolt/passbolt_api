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
 * @since         4.10.0
 */

namespace Passbolt\Metadata\Model\Rule;

use App\Model\Entity\Resource;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\TableRegistry;
use Passbolt\Metadata\Exception\MetadataKeyShareException;

class IsResourceSharedAndMetadataCanBeDecryptedWithSharedSessionKeyRule
{
    /**
     * Performs the check
     *
     * @param \App\Model\Entity\Resource $resource The entity to check
     * @param array $options Options passed to the check
     * @return bool
     */
    public function __invoke(Resource $resource, array $options): bool
    {
        // If the resource's metadata key type is not personal, the present rule does not apply
        $isPersonal = $resource->metadata_key_type === 'user_key';
        if ($isPersonal) {
            return true;
        }

        $metadataPrivateKeysTable = TableRegistry::getTableLocator()->get('Passbolt/Metadata.MetadataPrivateKeys');
        try {
            /** @var \Passbolt\Metadata\Model\Entity\MetadataPrivateKey $serverMetadataPrivateKey */
            $serverMetadataPrivateKey = $metadataPrivateKeysTable->find()
                ->where(['user_id IS' => null])
                ->order(['created' => 'DESC'])
                ->firstOrFail();
        } catch (\PDOException | RecordNotFoundException $exception) {
            $msg = __('Server metadata private key was not found.');
            throw new MetadataKeyShareException($msg, 500, $exception);
        }

        // TODO: implement the decryption of the metadata with the server private key
        // and cover with a test in MetadataResourcesUpdateServiceTest

        return true;
    }
}
