<?php

/**
 * HumanHelp - View helper to generate user comment HTML
 * 
 */

class HumanHelp_View_Helper_UserComments extends Zend_View_Helper_Abstract
{
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
                '<h3>On ' . date($this->_formatDate($comment->getCreatedAt())) . 
                ', ' . htmlspecialchars($comment->getAuthorName()) . ' said:</h3>' . 
                '<div class="comment-content">' . $this->_formatComment($comment->getComment()) . '</div>' .
                "</div>\n";

        return $html;
    }
    
    protected function _formatDate($timestamp)
    {
        return date('Y-m-d H:i:s', $timestamp);
    }
    
    protected function _formatComment($text)
    {
        return nl2br(htmlspecialchars($text));
    }
}