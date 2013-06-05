<?php

/**
 * 
 */

namespace Giniem\Controller;

use Zend\View\Model\ViewModel;

class IndexController extends GiniemController {

    private $uService;
    
    public function __construct($userService) {
        $this->uService = $userService;
    }
    
    /**
     * @return array All public actions in the controller
     */
    public function getPublicActions() {
        return array();
    }

    public function indexAction() {
        return new ViewModel();
    }

}
