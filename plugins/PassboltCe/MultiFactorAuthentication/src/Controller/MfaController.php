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
 * @since         2.5.0
 */
namespace Passbolt\MultiFactorAuthentication\Controller;

use App\Controller\AppController;
use App\Model\Entity\Role;
use Cake\Http\Exception\BadRequestException;
use Passbolt\MultiFactorAuthentication\Service\ClearMfaCookieInResponseService;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

abstract class MfaController extends AppController
{
    /**
     * @var \Passbolt\MultiFactorAuthentication\Utility\MfaSettings
     */
    protected $mfaSettings;

    /**
     * Initialization hook method.
     * Used to add common initialization code like loading components.
     *
     * @return void
     * @see SetMfaSettingsInRequestMiddleware::setMfaSettingsInRequestAttribute()
     */
    public function initialize(): void
    {
        parent::initialize();

        // Do not initialize if user is guest and login redirection is scheduled
        if ($this->User->role() !== Role::GUEST) {
            $this->mfaSettings = MfaSettings::get($this->User->getAccessControl());
        }
    }

    /**
     * Fail is organization do not allow this authentication provider
     *
     * @param string $provider name of the provider
     * @throws \Cake\Http\Exception\BadRequestException
     * @return void
     */
    protected function _orgAllowProviderOrFail(string $provider)
    {
        if (!$this->mfaSettings->getOrganizationSettings()->isProviderEnabled($provider)) {
            $msg = __('This authentication provider is not enabled for your organization.');
            throw new BadRequestException($msg);
        }
    }

    /**
     * Clear any dubious cookie if mfa check is required
     *
     * @return void
     */
    protected function _invalidateMfaCookie(): void
    {
        (new ClearMfaCookieInResponseService($this))->clearMfaCookie();
    }

    /**
     * Assert the request is not of json type.
     *
     * @return void
     * @throw BadRequestException if the request is of json type.
     */
    protected function _assertRequestNotJson(): void
    {
        if ($this->getRequest()->is('json')) {
            throw new BadRequestException(__('This functionality is not available using AJAX/JSON.'));
        }
    }
}
