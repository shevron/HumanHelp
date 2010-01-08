<?php

class HumanHelp_View_Helper_FormatCommentHtml extends Zend_View_Helper_Abstract
{
    public function formatCommentHtml($commentText)
    {
        return nl2br(htmlspecialchars($commentText));
    }
}