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

namespace Passbolt\MfaPolicies\Controller;

use App\Controller\AppController;
use App\Error\Exception\FormValidationException;
use Cake\Http\Exception\ForbiddenException;
use Passbolt\MfaPolicies\Form\MfaPoliciesSettingsForm;
use Passbolt\MfaPolicies\Model\Dto\MfaPolicySettings;
use Passbolt\MfaPolicies\Service\MfaPoliciesSetSettingsService;

class MfaPoliciesSettingsSetController extends AppController
{
    /**
     * Create/update MFA policies settings.
     *
     * @return void
     */
    public function post()
    {
        if (!$this->User->isAdmin()) {
            throw new ForbiddenException(
                __('Only administrators are allowed to create/update MFA policies settings.')
            );
        }

        $requestData = $this->getRequest()->getData();

        $form = new MfaPoliciesSettingsForm();

        if (!$form->execute($requestData)) {
            throw new FormValidationException(__('Could not validate the MFA policies settings.'), $form);
        }

        $setSettingsService = new MfaPoliciesSetSettingsService();

        $mfaPolicySettingsDto = MfaPolicySettings::createFromArray([
            'policy' => $form->getData('policy'),
            'remember_me_for_a_month' => $form->getData('remember_me_for_a_month'),
        ]);

        $settings = $setSettingsService->createOrUpdate($this->User->getExtendAccessControl(), $mfaPolicySettingsDto);

        $this->success(__('The operation was successful.'), $settings);
    }
}
