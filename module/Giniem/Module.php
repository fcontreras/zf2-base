<?php

/**
 *
 */

namespace Giniem;

use Giniem\Model\Users;
use Giniem\Model\UsersService;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module {

    public function onBootstrap(MvcEvent $e) {
        //Sets the locale of the translator
        $translator = $e->getApplication()->getServiceManager()->get('translator');
        //To make this work we need to install the Intl extension.
        $translator
          ->setLocale(\Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']))
          ->setFallbackLocale('en_US');
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        date_default_timezone_set('America/Managua');
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getControllerConfig() {
        return array(
            'factories' => array(
                'index' => function(\Zend\Mvc\Controller\ControllerManager $cm) {
                    $uService = $cm->getServiceLocator()->get('UsersService');
                    $controller = new \Giniem\Controller\IndexController($uService);

                    return $controller;
                }
            )
        );
    }

    public function getServiceConfig() {
        return array(
            'factories' => array(
                'AuthService' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $dbTableAdapter = new DbTableAuthAdapter($dbAdapter,
                                    'users', 'username', 'password', 'MD5(?)');

                    $authService = new AuthenticationService();
                    $authService->setAdapter($dbTableAdapter);

                    return $authService;
                },
                'UsersService' => function($sm) {
                    $tableGateway = $sm->get('UsersGateway');
                    $service = new UsersService($tableGateway);

                    return $service;
                },
                'UsersGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Users());

                    return new TableGateway('users', $dbAdapter, null, $resultSetPrototype);
                },
            )
        );
    }

}
