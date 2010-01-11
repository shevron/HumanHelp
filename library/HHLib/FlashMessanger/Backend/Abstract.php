<?php

abstract class HHLib_FlashMessanger_Backend_Abstract
{
    protected $_config = array();
    
    public function setConfig($config = array())
    {
        if ($config instanceof Zend_Config) {
            $config = $config->toArray();
        }
        
        if (! is_array($config)) {
            throw new HHLib_Exception("Configuration is expected to be an array or a Zend_Config object, got " . gettype($config));
        }
        
        $this->_config = array_merge($this->_config, $config);
    }
    
    /**
     * Store a message to the backend storage
     * 
     * @param HHLib_FlashMessanger_Message $message
     */
    abstract public function addMessage(HHLib_FlashMessanger_Message $message);
    
        
    abstract public function getMessages();
    
    abstract public function clearMessages();
}