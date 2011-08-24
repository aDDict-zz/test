<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
  /*protected function _initRoutes() {
    $ctrl = Zend_Controller_Front::getInstance();
    
    $router = $ctrl->getRouter();
    
    $router->addRoute(
            'test',
            new Zend_Controller_Router_Route('test/:stub',
                array('controller'  => 'test',
                      'action'      => 'index'))
    ); 

    $router->addRoute(
            'artist_detail_gallery',
            new Zend_Controller_Router_Route('artists/:stub/:gallery',
                                            array('controller' => 'artists',
                                                  'action' => 'gallery'))
    );

    
    $db = Zend_Db::factory(
      'PDO_MYSQL', array (
        'host'     => 'localhost',    
        'username' => 'root',                 
        'password' => 'v',          
        'dbname'   => 'maxima')
    );
    //print_r($db);
  }*/
  
  /*$db = Zend_Db::factory(
    'PDO_MYSQL', array (
      'host'     => 'localhost',    
      'username' => 'root',                 
      'password' => 'v',          
      'dbname'   => 'maxima')
  );*/

}

