<?php

/**
 * 
 */

namespace Giniem\Controller;

use Zend\View\Model\ViewModel;

class AuthController extends GiniemController {

    /**
     * @return array All public actions in the controller
     */
    public function getPublicActions() {
        return array('index', 'authenticate');
    }

    public function indexAction() {
        if ($this->isAuthenticated()) {
            $this->debug("User already authenticated, redirecting to home.");
            $this->redirect()->toRoute('home');
        }

        $result = new ViewModel();
        $result->setTerminal(true);
        return $result;
    }

    public function authenticateAction() {
        $request = $this->getRequest();
        $username = $request->getPost('username');
        $password = $request->getPost('password');
        $adapter = $this->getAuthService()->getAdapter();
        $adapter->setIdentity($username)
                ->setCredential($password);

        $this->info("Found user: $username with password");

        $result = $this->getAuthService()->authenticate();

        if ($result->isValid()) {
            //Storing basic values in the cookies
            $storage = $this->getAuthService()->getStorage();
            $storage->write($adapter->getResultRowObject(array(
                'username', 'first_name', 'last_name', 'id'
            )));
            
            $this->debug("Welcome user $username");
            $this->redirect()->toRoute('home');
        } else {
            $this->debug("Invalid credentials");
            $this->redirect()->toRoute('auth');
        }
    }

    public function logoutAction() {
        if ($this->isAuthenticated()) {
            $identity = $this->getAuthService()->getIdentity();
            $username = $identity->username;
            $this->debug("Goodbye $username!");

            $this->getAuthService()->clearIdentity();
        }
        $this->redirect()->toRoute('auth');
    }

}