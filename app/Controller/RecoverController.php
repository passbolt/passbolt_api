<?php
/**
 * Account Recovery Controller
 *
 * @copyright (c) 2016-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('Core', 'Validation');

class RecoverController extends AppController {

/**
 * This controller uses the User model
 *
 * @var array
 */
    public $uses = ['User','AuthenticationToken'];

/**
 * @var array components used by this controller
 */
    public $components = [
        'EmailNotificator',
    ];

/**
 * Called before the controller action. Used to manage access right
 *
 * @return void
 * @link http://book.cakephp.org/2.0/en/controllers.html#request-life-cycle-callbacks
 */
    public function beforeFilter() {
        $allow = [
            'recover',
            'recover_thankyou',
        ];
        $this->Auth->allow($allow);
        parent::beforeFilter();
    }

/**
 * Recover an existing account page.
 *
 * @throws Exception
 * @return void
 */
    public function recover() {
        $this->layout = 'login';

        if (!empty($this->request->data)) {
            try {
                // No user or invalid data provided
                if (!isset($this->request->data['User']) || empty($this->request->data['User']) ||
                    !isset($this->request->data['User']['username']) || empty($this->request->data['User']['username'])) {
                    throw new BadRequestException(__('Please provide an email address.'));
                }
                if (!Validation::email($this->request->data['User']['username'])) {
                    throw new BadRequestException(__('Please provide a valid email address.'));
                }

                // User doesn't exist.
                $user = $this->User->findByUsername($this->request->data['User']['username']);
                if (empty($user)) {
                    throw new BadRequestException(__('This user does not exist. Please register and complete the setup first.'));
                }

                // User has been deleted
                if ($user['User']['deleted']) {
                    throw new BadRequestException(__('This user has been deleted. Please contact your administrator.'));
                }

                // User is not activated
                if (!$user['User']['active']) {
                    throw new BadRequestException(__('This user is not active. Please complete the setup first.'));
                }

                // Create the setup authentication token
                $saveToken = $this->AuthenticationToken->generate($user['User']['id']);
                if (!$saveToken) {
                    throw new InternalErrorException(__('Something went wrong and it was not possible to create a recovery token. Please try again later.'));
                }
            }
            catch (Exception $e) {
                if ($this->request->is('json')) {
                    throw $e;
                }

                $this->User->invalidate('username', $e->getMessage());
                return;
            }

            // Send notification email
            $this->EmailNotificator->accountRecoveryNotification(
                $user['User']['id'],
                [
                    'token' => $saveToken,
                    'creator_id' => $user['User']['id'],
                ]);

            $this->redirect('/recover/thankyou');
        }
    }

/**
 * Thank you page after recovery.
 *
 * @throws Exception
 * @return void
 */
    public function recover_thankyou() {
        $this->layout = 'login';
    }

}