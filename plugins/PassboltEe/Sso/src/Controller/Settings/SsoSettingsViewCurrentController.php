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
 * @since         3.9.0
 */

namespace Passbolt\Sso\Controller\Settings;

use App\Controller\AppController;
use App\Model\Entity\Role;
use App\Model\Table\Dto\FindIndexOptions;
use Cake\Event\EventInterface;
use Passbolt\Sso\Service\SsoSettings\SsoSettingsGetService;

class SsoSettingsViewCurrentController extends AppController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        $this->Authentication->allowUnauthenticated(['viewCurrent']);

        return parent::beforeFilter($event);
    }

    /**
     * @return void
     */
    public function viewCurrent(): void
    {
        switch ($this->User->role()) {
            case Role::ADMIN:
                $findIndexOptions = (new FindIndexOptions())->allowContains(['data']);
                $options = $this->QueryString->get($findIndexOptions->getAllowedOptions());
                $contain = $options['contain']['data'] ?? false;
                $serviceSettingsDto = (new SsoSettingsGetService())->getActiveOrDefault($contain);
                $result = $serviceSettingsDto->toArray();
                break;
            default:
            case Role::USER:
            case Role::GUEST:
                $serviceSettingsDto = (new SsoSettingsGetService())->getActiveOrDefault(false);
                $result = ['provider' => $serviceSettingsDto->getProvider()];
                break;
        }

        $this->success(__('The operation was successful.'), $result);
    }
}
