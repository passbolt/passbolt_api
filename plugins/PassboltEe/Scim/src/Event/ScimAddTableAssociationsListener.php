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
 * @since         5.5.0
 */
namespace Passbolt\Scim\Event;

use App\Model\Table\UsersTable;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Passbolt\Scim\Model\Entity\ScimEntry;

class ScimAddTableAssociationsListener implements EventListenerInterface
{
    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            'Model.initialize' => 'addTableAssociations',
        ];
    }

    /**
     * Add SCIM related associations
     *
     * @param \Cake\Event\EventInterface $event Event
     * @return void
     */
    public function addTableAssociations(EventInterface $event): void
    {
        $table = $event->getSubject();
        if ($table instanceof UsersTable) {
            $this->addScimUsersAssociations($table);
        }
    }

    /**
     * @param \App\Model\Table\UsersTable $table Users table to add association on
     * @return void
     */
    protected function addScimUsersAssociations(UsersTable $table): void
    {
        if ($table->hasAssociation('ScimEntries')) {
            return;
        }
        $table->hasOne('ScimEntries', [
            'className' => 'Passbolt/Scim.ScimEntries',
            'foreignKey' => 'foreign_key',
            'conditions' => [
                'ScimEntries.foreign_model' => ScimEntry::FOREIGN_MODEL_USERS,
            ],
        ]);
    }
}
