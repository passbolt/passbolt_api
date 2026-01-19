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
use App\Error\Exception\CustomValidationException;
use App\Model\Entity\Role;
use App\Service\Users\UserRecoverServiceInterface;
use Cake\Core\Configure;
use Cake\Event\EventInterface;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Utility\Hash;
use Passbolt\SelfRegistration\Service\DryRun\SelfRegistrationDryRunServiceInterface;

/**
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\AuthenticationTokensTable $AuthenticationTokens
 */
class UsersRecoverController extends AppController
{
    public const RECOVER_SUCCESS_EVENT_NAME = 'UsersRecoverController.recoverPost.success';
    public const PREVENT_EMAIL_ENUMERATION_CONFIG_KEY = 'passbolt.security.preventEmailEnumeration';

    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        $this->Authentication->allowUnauthenticated(['recoverGet', 'recoverPost']);

        parent::beforeFilter($event);
    }

    /**
     * Recover user action GET
     * Display a recovery form
     *
     * @param \App\Service\Users\UserRecoverServiceInterface $userRecoverService User recover service
     * @return void
     */
    public function recoverGet(UserRecoverServiceInterface $userRecoverService)
    {
        // Do not allow logged in user to recover
        if ($this->User->role() !== Role::GUEST) {
            $this->Authentication->logout();
        }

        $this->set('title', Configure::read('passbolt.meta.description'));
        $userRecoverService->setTemplate($this->viewBuilder());
        $this->success();
    }

    /**
     * Register user action POST
     *
     * @param \App\Service\Users\UserRecoverServiceInterface $userRecoverService User recover service
     * @param \Passbolt\SelfRegistration\Service\DryRun\SelfRegistrationDryRunServiceInterface $dryRunService Service to detect if the user can self register
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException if the username is not valid
     * @throws \Cake\Http\Exception\BadRequestException if the username is not provided
     * @throws \Cake\Http\Exception\InternalErrorException if the self registration settings in the DB are not valid
     */
    public function recoverPost(
        UserRecoverServiceInterface $userRecoverService,
        SelfRegistrationDryRunServiceInterface $dryRunService
    ) {
        $this->assertJson();

        // Do not allow logged-in user to recover
        if (!in_array($this->User->role(), [Role::GUEST, Role::ADMIN])) {
            throw new ForbiddenException(__('Only guests are allowed to recover an account. Please logout first.'));
        }

        try {
            $userRecoverService->recover($this->User->getAccessControl());
        } catch (NotFoundException $exception) {
            $this->handleException($exception, $dryRunService, $this->request->getData());
        }
        $this->success(__('Recovery process started, check your email.'));
    }

    /**
     * Only throw exception if email is from allowed list of self-registration,
     * otherwise pretend everything is fine to prevent user enumeration using email.
     *
     * @param \Cake\Http\Exception\NotFoundException $exception Exception to handle.
     * @param \Passbolt\SelfRegistration\Service\DryRun\SelfRegistrationDryRunServiceInterface $dryRunService Service to detect if the user can self register
     * @param array $requestData Request data.
     * @return void
     */
    private function handleException(
        NotFoundException $exception,
        SelfRegistrationDryRunServiceInterface $dryRunService,
        array $requestData
    ): void {
        if (!$dryRunService->isSelfRegistrationOpen()) {
            return;
        }

        $result = false;
        try {
            $result = $dryRunService->canGuestSelfRegister(['email' => $requestData['username']]);
        } catch (CustomValidationException $exception) {
            // Do not throw email domain not allowed exception
            $errors = $exception->getErrors();
            if (!Hash::check($errors, 'email.checkEmailDomainIsAllowed')) {
                throw $exception;
            }
        }

        if ($result) {
            throw $exception;
        }
    }
}
