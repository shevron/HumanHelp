<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initRequest(array $options = array())
    {
        // Ensure front controller instance is present, and fetch it
        $this->bootstrap('FrontController');
        $front = $this->getResource('FrontController'); /* @var $front Zend_Controller_Front */

        // Set up routes
        $front->getRouter()->addConfig(
            new Zend_Config(include APPLICATION_PATH . '/configs/routes.php')
        );
        
        // Initialize the request object
        $request = new Zend_Controller_Request_Http();

        // Add it to the front controller
        $front->setRequest($request);

        // Bootstrap will store this value in the 'request' key of its container
        return $request;
    }
    
    protected function _initView()
    {
        $this->bootstrap('request');
        
        // Initialize view
        $view = new Zend_View();
        $view->doctype('XHTML1_STRICT');
        $view->headTitle()->setSeparator(' » ');

        // Save the base URL
        $view->baseUrl = $this->getResource('request')->getBaseUrl();
        
        // Add it to the ViewRenderer
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
            'ViewRenderer'
        );
        $viewRenderer->setView($view);
        
        // Add some stylesheet
        $view->headLink()->appendStylesheet($view->baseUrl . '/css/default.css');

        $view->addHelperPath(APPLICATION_PATH . '/views/helpers', 'HumanHelp_View_Helper_');
        
        // Return it, so that it can be stored by the bootstrap
        return $view;
    }
    
    protected function _initDb()
    {

    }
    
    protected function _initAutoloader()
    {
        $autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'HumanHelp_',
            'basePath'  => APPLICATION_PATH
        ));
        return $autoloader;
    }
    
    protected function _initConfig()
    {
        Zend_Registry::set('config', new Zend_Config(include APPLICATION_PATH . '/configs/config.php'));
    }
}

