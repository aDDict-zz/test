<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
  protected function _initRoutes() {  
    $ctrl = Zend_Controller_Front::getInstance();
    $router = $ctrl->getRouter();
    $router->addRoute(
        'group',
        new Zend_Controller_Router_Route(
          'group/:stub',
          array(
            'controller' => 'group',
            'action' => 'show'
          )
        )
    );
  }
}

