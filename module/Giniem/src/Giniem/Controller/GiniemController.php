<?php

/**
 * 
 */
namespace Giniem\Controller;

use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\Controller\AbstractActionController;

abstract class GiniemController extends AbstractActionController {
    
    private $logger;
    private $authService;

    /**
     * 
     * @return Zend\Authentication\AuthenticationService
     */
    protected function getAuthService() {
        if (!isset($this->authService)) {
            $this->authService = $this->getServiceLocator()->get('AuthService');
        }
        
        return $this->authService;
    }
    
    /**
     * 
     * @return Zend\Log\Logger
     */
    private function getLogger() {
        if (!isset($this->logger)) {
            $this->logger = $this->getServiceLocator()->get('Zend\Log');
        }
        
        return $this->logger;
    }

    /**
     * Returns true if and only if an identity is available from storage
     *
     * @return bool
     */
    protected function isAuthenticated() {
        return $this->getAuthService()->hasIdentity();
    }
    
    protected function getUserData() {
        if($this->isAuthenticated()) {
            
        } else {
            return false;
        }
    }
    
    /**
     * Logs a message as debug
     * @param String $message
     */
    protected function debug($message) {
        $this->getLogger()->debug($message);
    }
    
    /**
     * Logs a messge as info
     * @param String $message
     */
    protected function info($message) {
        $this->getLogger()->debug($message);
    }
    
    /**
     * Should validate if the user is authenticated for protected
     * actions, and redirect if necesary.
     * 
     * @param \Members\Controller\EventManagerInterface $events
     * @return mixed
     */
    public function setEventManager(EventManagerInterface $events)
    {
        parent::setEventManager($events);

        $controller = $this;
        $events->attach('dispatch', function ($e) use ($controller) {
            $controllerName = $controller->params('controller');
            $actionName = $controller->params('action');

            //We create temp vars cause the event dispatch is called before
            //herachy functions are available.
            $temp_logger = $controller->getServiceLocator()->get('Zend\Log');
            $temp_auth = $controller->getServiceLocator()->get('AuthService');
            
            //Loggin the controller action
            $smallCName = end(explode("\\", $controllerName));
            $temp_logger->info("Loading $smallCName/$actionName");
            
            if (!in_array($actionName, $controller->getPublicActions())
                    && !$temp_auth->hasIdentity()) {
                //destroy temp vars
                unset($temp_logger);
                unset($temp_auth);

                return $controller->redirect()->toRoute('auth');
            }
        }, 100); // execute before executing action logic
    }
    
    /**
     * @return array An array with the public (non protected) actions.
     */
    abstract public function getPublicActions();
}
