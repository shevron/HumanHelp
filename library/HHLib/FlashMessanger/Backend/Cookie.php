<?php

class HHLib_FlashMessanger_Backend_Cookie extends HHLib_FlashMessanger_Backend_Abstract
{
    protected $_messages = array();
    
    public function __construct()
    {
        $this->setConfig(array(
            'cookieName'     => 'HHLibMessages',
            'cookieLifetime' => 60,
            'cookiePath'     => '/',
            'cookieDomain'   => '',
        ));
        
        $this->_loadRequestMessages();
    }
    
    public function getMessages()
    {
        return $this->_messages;    
    }
    
    public function clearMessages()
    {
        $this->_messages = array();
        setcookie(
            $this->_config['cookieName'],
            '',
            0,
            $this->_config['cookiePath'],
            $this->_config['cookieDomain']
        );
    }
    
    public function addMessage(HHLib_FlashMessanger_Message $message)
    {
        if (headers_sent($file, $line)) {
            throw new HHLib_Exception("Can't add message to Cookie backend: headers already sent in $file:$line");
        }
        
        $this->_messages[] = $message;
        
        $this->_setMessageCookie();
    }
    
    protected function _setMessageCookie()
    {
        $messageCookie = array();
        foreach($this->_messages as $msg) {
            $messageCookie[] = base64_encode($msg->getMessage()) . ':' . base64_encode($msg->getClass());
        }
        
        $messageCookie = implode('|', $messageCookie);
        setcookie(
            $this->_config['cookieName'],
            $messageCookie,
            time() + $this->_config['cookieLifetime'],
            $this->_config['cookiePath'],
            $this->_config['cookieDomain']
        );
    }
    
    protected function _loadRequestMessages()
    {
        if (isset($_COOKIE[$this->_config['cookieName']])) {
            $messages = explode('|', $_COOKIE[$this->_config['cookieName']]);
            foreach($messages as $msgText) {
                $parts = explode(':', $msgText);
                if (count($parts) == 2) {
                    $msg = new HHLib_FlashMessanger_Message(base64_decode($parts[0]), base64_decode($parts[1]));
                    $this->_messages[] = $msg; 
                }
            }
        }
    }
}