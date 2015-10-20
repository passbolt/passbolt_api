<?php
/**
 * Auth Controller
 *
 * @copyright   Copyright 2015, Passbolt.com
 * @license     http://www.passbolt.com/license
 */
class AuthController extends AppController {

    /**
     * Login
     *
     * @access public
     */
    public function login() {
        // check if the user Authentication worked
        // someone can not remain anonymous forever
        if (!$this->Auth->login() || User::isAnonymous()) {
            $this->layout = 'login';
            $this->view = '/Auth/login';
            if ($this->request->is('post')) {
                $this->request->data['User']['password'] = null;
                $this->Message->error(__('Invalid username or password, try again'), array('throw' => false));
            }
            return;
        }
        // avoid looping if the requested URL is logout
        if ($this->Auth->redirectUrl() == '/logout' || $this->Auth->redirectUrl() == '/login') {
            $this->redirect('/');
        } else {
            return $this->redirect($this->Auth->redirectUrl());
        }
    }

    /**
     *
     */
    public function verify()
    {
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

}