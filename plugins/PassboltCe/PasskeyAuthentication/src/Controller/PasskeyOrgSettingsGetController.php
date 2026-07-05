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
 * @since         5.14.0
 */
namespace Passbolt\PasskeyAuthentication\Controller;

use Cake\Event\EventInterface;
use Passbolt\PasskeyAuthentication\Service\PasskeyOrganizationSettingsService;

class PasskeyOrgSettingsGetController extends PasskeyController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        // The login page (no session yet) needs this to know whether to show the passkey button.
        $this->Authentication->allowUnauthenticated(['get']);

        return parent::beforeFilter($event);
    }

    /**
     * Whether passkey login is enabled for the organization.
     *
     * @param \Passbolt\PasskeyAuthentication\Service\PasskeyOrganizationSettingsService $settings Settings service
     * @return void
     */
    public function get(PasskeyOrganizationSettingsService $settings): void
    {
        $this->success(__('The operation was successful.'), [
            'enabled' => $settings->isEnabled(),
        ]);
    }
}
