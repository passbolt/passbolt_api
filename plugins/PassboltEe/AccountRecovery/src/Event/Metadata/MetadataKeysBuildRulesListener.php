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
namespace Passbolt\AccountRecovery\Event\Metadata;

use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Passbolt\AccountRecovery\Model\Rule\Metadata\IsNotAccountRecoveryFingerprintRule;
use Passbolt\Metadata\Model\Table\MetadataKeysTable;

class MetadataKeysBuildRulesListener implements EventListenerInterface
{
    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            'Model.buildRules' => 'handleAfterBuildRules',
        ];
    }

    /**
     * @param \Cake\Event\EventInterface $event Event
     * @return void
     */
    public function handleAfterBuildRules(EventInterface $event): void
    {
        $model = $event->getSubject();
        if (!$model instanceof MetadataKeysTable) {
            return;
        }

        $rules = $event->getData('rules');
        $rules->add(new IsNotAccountRecoveryFingerprintRule(), 'isNotAccountRecoveryFingerprint', [
            'errorField' => 'fingerprint',
            'message' => __('You cannot reuse the account recovery key.'),
        ]);
    }
}
