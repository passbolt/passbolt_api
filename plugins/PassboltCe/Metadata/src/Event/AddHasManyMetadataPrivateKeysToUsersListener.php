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
namespace Passbolt\Metadata\Event;

use App\Model\Table\UsersTable;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;

class AddHasManyMetadataPrivateKeysToUsersListener implements EventListenerInterface
{
    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return ['Model.initialize' => 'addHasManyMetadataPrivateKeysAssociation'];
    }

    /**
     * Attach the hasMany metadata private keys relation to users model.
     *
     * @param \Cake\Event\EventInterface $event Event object.
     * @return void
     */
    public function addHasManyMetadataPrivateKeysAssociation(EventInterface $event): void
    {
        $subject = $event->getSubject();
        if (!$subject instanceof UsersTable) {
            return;
        }

        $subject->hasMany('MetadataPrivateKeys', [
            'className' => 'Passbolt/Metadata.MetadataPrivateKeys',
            'joinType' => 'LEFT',
        ]);
    }
}
