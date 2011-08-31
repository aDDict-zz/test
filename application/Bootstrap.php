<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
  
	protected function _initRoutes() {  
    $ctrl = Zend_Controller_Front::getInstance();
    $router = $ctrl->getRouter();
    $router->addRoute(
        'group', new Zend_Controller_Router_Route(
          'group/:stub',
          array(
            'controller'  => 'group',
            'action'      => 'show'
          )
        )
    );
    /*$router->addRoute(
        'login', new Zend_Controller_Router_Route(
          'process/',
          array(
            'controller'  => 'login',
            'action'      => 'process'
          )
        )
    );*/
  }
  
  protected function _initPaths() {
    
    define('WEB_ROOT', "/newStuff");
    
    $rootDir = dirname(dirname(__FILE__));
    define('ROOT_DIR', $rootDir);
    set_include_path(get_include_path()
      /*. PATH_SEPARATOR . ROOT_DIR . '/library/'*/
      . PATH_SEPARATOR . ROOT_DIR . '/application/forms/'
    );
    Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);
  }
}

