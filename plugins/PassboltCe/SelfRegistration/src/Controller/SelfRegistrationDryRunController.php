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
 * @since         3.10.0
 */
namespace Passbolt\SelfRegistration\Controller;

use App\Controller\AppController;
use Cake\Event\EventInterface;
use Cake\Http\Exception\BadRequestException;
use Passbolt\SelfRegistration\Service\DryRun\SelfRegistrationDryRunServiceInterface;

class SelfRegistrationDryRunController extends AppController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        $this->Authentication->allowUnauthenticated(['dryRun']);

        return parent::beforeFilter($event);
    }

    /**
     * Self Registration Dry run action
     * Detects if a guest can perform self registration
     *
     * @param \Passbolt\SelfRegistration\Service\DryRun\SelfRegistrationDryRunServiceInterface $dryRunService Service to detect if the user can self register
     * @return void
     * @throws \Cake\Http\Exception\InternalErrorException if the settings in the DB are not valid
     */
    public function dryRun(SelfRegistrationDryRunServiceInterface $dryRunService): void
    {
        $this->User->assertIsGuest();
        $data = $this->getRequest()->getData();
        if (!is_array($data)) {
            throw new BadRequestException(_('The data should be an array.'));
        }
        $dryRunService->canGuestSelfRegister($data);

        $this->success(__('The operation was successful.'));
    }
}
