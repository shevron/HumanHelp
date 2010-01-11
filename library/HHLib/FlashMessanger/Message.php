<?php

class HHLib_FlashMessanger_Message
{
    protected $_message = null;
    
    protected $_class   = null;
    
    public function __construct($message, $class = '')
    {
        $this->_message = (string) $message;
        $this->_class   = (string) $class;
    }
    
    /**
     * Get the message text
     * 
     * @return string
     */
    public function getMessage()
    {
        return $this->_message;
    }
    
    /**
     * Get the message class, or empty string if not set
     * 
     * @return string
     */
    public function getClass()
    {
        return $this->_class;
    }
    
    /**
     * Cast object to string
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->_message;
    }
}