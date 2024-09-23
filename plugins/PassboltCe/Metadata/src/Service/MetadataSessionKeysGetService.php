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
namespace Passbolt\Metadata\Service;

use App\Utility\UserAccessControl;
use Cake\ORM\Locator\LocatorAwareTrait;

class MetadataSessionKeysGetService
{
    use LocatorAwareTrait;

    /**
     * @param \App\Utility\UserAccessControl $uac UAC.
     * @return \Passbolt\Metadata\Model\Entity\MetadataSessionKey[]
     */
    public function get(UserAccessControl $uac): array
    {
        /** @var \Passbolt\Metadata\Model\Table\MetadataSessionKeysTable $metadataSessionKeysTable */
        $metadataSessionKeysTable = $this->fetchTable('Passbolt/Metadata.MetadataSessionKeys');

        return $metadataSessionKeysTable->find()->where(['user_id' => $uac->getId()])->toArray();
    }
}
