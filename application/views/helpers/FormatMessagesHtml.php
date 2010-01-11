<?php

class HumanHelp_View_Helper_FormatMessagesHtml extends Zend_View_Helper_Abstract
{
    public function formatMessagesHtml(array $messages)
    {
        $html = '';
        
        foreach($messages as $message) { /* @var $message HHLib_FlashMessanger_Message */
            if (! $message instanceof HHLib_FlashMessanger_Message) {
                throw new ErrorException("Message is not a message object, cannot format");
            }
            
            $html .= '<div class="message ' . htmlspecialchars($message->getClass()) . '">' . 
                htmlspecialchars($message->getMessage()) . "</div>\n";
        }
        
        return $html;
    }
}