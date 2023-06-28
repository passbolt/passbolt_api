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
 * @since         2.0.0
 */
namespace App\Controller\Users;

use App\Controller\AppController;
use App\Model\Entity\Role;
use App\Service\Users\UserRegisterServiceInterface;
use Cake\Core\Configure;
use Cake\Event\EventInterface;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Passbolt\SelfRegistration\Service\DryRun\SelfRegistrationDryRunServiceInterface;

/**
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersRegisterController extends AppController
{
    public const USERS_REGISTER_EVENT_NAME = 'UsersRegisterController.register.success';

    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        $this->Authentication->allowUnauthenticated(['registerGet', 'registerPost']);

        return parent::beforeFilter($event);
    }

    /**
     * Register user action GET
     * Display a registration form
     *
     * @throws \Cake\Http\Exception\ForbiddenException if the current user is logged in
     * @param \App\Service\Users\UserRegisterServiceInterface $userRegisterService Registration service
     * @param \Passbolt\SelfRegistration\Service\DryRun\SelfRegistrationDryRunServiceInterface $dryRunService Service to assess to availability of self registration
     * @return void
     * @throws \Cake\Http\Exception\NotFoundException if self registration is not open
     */
    public function registerGet(
        UserRegisterServiceInterface $userRegisterService,
        SelfRegistrationDryRunServiceInterface $dryRunService
    ): void {
        $this->assertIsSelfRegistrationOpen($dryRunService);
        // Do not allow logged in user to register
        if ($this->User->role() !== Role::GUEST) {
            throw new ForbiddenException(__('Only guests are allowed to register.'));
        }

        $this->set('title', Configure::read('passbolt.meta.description'));
        $userRegisterService->setTemplate($this->viewBuilder());
    }

    /**
     * Register user action POST
     *
     * @throws \Cake\Http\Exception\ForbiddenException if the current user is logged in
     * @param \App\Service\Users\UserRegisterServiceInterface $userRegisterService Service
     * @param \Passbolt\SelfRegistration\Service\DryRun\SelfRegistrationDryRunServiceInterface $dryRunService Service to assess to availability of self registration
     * @return void
     */
    public function registerPost(
        UserRegisterServiceInterface $userRegisterService,
        SelfRegistrationDryRunServiceInterface $dryRunService
    ): void {
        $this->assertJson();

        $this->assertIsSelfRegistrationOpen($dryRunService);

        // Do not allow logged in user to register
        if ($this->User->role() !== Role::GUEST) {
            throw new ForbiddenException(__('Only guests are allowed to register.'));
        }

        // Assert that the user can self register, based on the payload and the self registration settings
        $dryRunService->canGuestSelfRegister(['email' => $this->getRequest()->getData('username')]);

        $user = $userRegisterService->register();

        $this->success(__('The operation was successful.'), $user);
    }

    /**
     * @param \Passbolt\SelfRegistration\Service\DryRun\SelfRegistrationDryRunServiceInterface $dryRunService dry run service
     * @return void
     * @throws \Cake\Http\Exception\NotFoundException if the user cannot register
     */
    protected function assertIsSelfRegistrationOpen(SelfRegistrationDryRunServiceInterface $dryRunService): void
    {
        if (!$dryRunService->isSelfRegistrationOpen()) {
            $msg = __('Registration is not opened to public.') . ' ';
            $msg .= __('Please contact your administrator.');
            throw new NotFoundException($msg);
        }
        if (Configure::read(UsersRecoverController::PREVENT_EMAIL_ENUMERATION_CONFIG_KEY)) {
            $msg = __('Registration is not opened to public.') . ' ';
            $msg .= __('This is due to a security setting.') . ' ';
            $msg .= __('Please contact your administrator.');
            throw new NotFoundException($msg);
        }
    }
}
