<?php

/**
 * Main Flash Messanger class
 *  
 * @author shahar
 * @todo   Implement Iterator on this class
 */

class HHLib_FlashMessanger implements Countable /*, Iterator */
{
    /**
     * Storage backend 
     * 
     * @var HHLib_FlashMessanger_Backend_Abstract
     */
    protected $_backend = null;
    
    protected $_config = array(
        'defaultClass' => 'info-message'
    );
    
    public function __construct($backend, $config = array())
    {
        if (is_string($backend)) {
            Zend_Loader::loadClass($backend);
            $backend = new $backend();
        }
        
        if ($backend instanceof HHLib_FlashMessanger_Backend_Abstract) {
            $this->_backend = $backend;
            $this->_backend->setConfig($config);
            
        } else {
            throw new HHLib_Exception("Backend should be either a backend object or a backend class name");
        }
        
        $this->setConfig($config);
    }
    
    public function setConfig($config)
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
     * Get count of messages
     * 
     * @return integer
     */
    public function count()
    {
        return count($this->_backend->getMessages());
    }
    
    /**
     * Add a message to the flash memory
     * 
     * @param HHLib_FlashMessanger_Message | string $message
     * @param string                                $class
     */
    public function add($message, $class = null)
    {
        if (is_string($message)) {
            if (! $class) $class = $this->_config['defaultClass'];
            $message = new HHLib_FlashMessanger_Message($message, $class);
        }
        
        if (! $message instanceof HHLib_FlashMessanger_Message) {
            throw new HHLib_Exception("Message should be either a Message object or a string");
        }
        
        $this->_backend->addMessage($message);
    }
    
    /**
     * Get all messages
     * 
     * @return array
     */
    public function getAllMessages()
    {
        $messages = $this->_backend->getMessages();
        $this->_backend->clearMessages();
        
        return $messages;
    }
}