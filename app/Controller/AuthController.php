<?php
/**
 * Auth Controller
 *
 * @copyright   Copyright 2015, Passbolt.com
 * @license     http://www.passbolt.com/license
 */
class AuthController extends AppController {

    public function beforeFilter() {
        $this->Auth->allow();
        parent::beforeFilter();
    }

    /**
     * Login
     * @access public
     */
    public function login() {
        // check if the user Authentication worked
        if (!$this->Auth->login()) {
            $this->layout = 'login';
            $this->view = '/Auth/login';
        } else {
            if ($this->request->is('json')) {
                // We do not redirect since the Javascript app will take care of this
                // Also it messes up with the GPGAuth headers if we do
            } else {
                return $this->redirect($this->Auth->redirectUrl());
            }
        }
    }

    /**
     *
     */
    public function verify() {
        if ($this->request->is('post')) {
            $this->Auth->login();
        } else {
            $key['fingerprint'] = Configure::read('GPG.serverKey.fingerprint');
            $file = new File(Configure::read('GPG.serverKey.public'));
            if($file->exists()) {
                $key['keydata'] = $file->read();
                $this->set('data',$key);
                if(!$this->request->is('json')) {
                    $this->layout = 'empty';
                }
                return $this->Message->success();
            } else {
                return $this->Message->error(
                    __('The public key for this passbolt instance was not found.'),
                    array('code' => '400')
                );
            }
        }
    }

    /**
     * Logout
     *
     * @access public
     */
    public function logout() {
        $this->redirect($this->Auth->logout());
    }

    /**
     * Used to return partial login components to be used by the plugin to update the login page
     */
    public function partials($case) {
        if($this->request->isAjax()) {
            $allowed_case = array(
                'default', 'noconfig', 'stage0'
            );
            foreach ($allowed_case as $c) {
                if ($c === $case) {
                    $this->render('../Elements/public/auth/' . $case);
                    return;
                }
            }
            $this->render('../Elements/public/auth/default');
            return;
        }
        $this->redirect('/auth/login');
        return false;
    }
}