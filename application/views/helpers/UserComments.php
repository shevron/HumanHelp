<?php

/**
 * HumanHelp - View helper to generate user comment HTML
 * 
 */

class HumanHelp_View_Helper_UserComments extends Zend_View_Helper_Abstract
{
    protected $_dateFormat = 'Y-m-d H:i:s';
    
    public function __construct()
    {
        $config = Zend_Registry::get('config');
        if ($config && $config->dateFormat) $this->_dateFormat = $config->dateFormat;
    }
    
    public function userComments(HumanHelp_Model_Page $page)
    {
        $comments = $page->getComments();
        if (empty($comments)) {
            return 'There are currently no user contributed comments for this page';
        } else {
            $html = '';
            foreach($comments as $comment) {
                $html .= $this->_buildCommentHtml($comment);
            }
            
            return $html;
        }
    }
    
    protected function _buildCommentHtml(HumanHelp_Model_Comment $comment)
    {
        $html = '<div class="comment" id="comment-' . $comment->getId() . '">' . 
                '<h3>On ' . $this->_formatDate($comment->getCreatedAt()) . 
                ', ' . htmlspecialchars($comment->getAuthorName()) . ' said:</h3>' . 
                '<div class="comment-content">' . $this->_formatComment($comment->getComment()) . '</div>' .
                "</div>\n";

        return $html;
    }
    
    protected function _formatDate($timestamp)
    {
        return date($this->_dateFormat, $timestamp);
    }
    
    protected function _formatComment($text)
    {
        return nl2br(htmlspecialchars($text));
    }
}