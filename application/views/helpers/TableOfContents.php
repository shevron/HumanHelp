<?php

class HumanHelp_View_Helper_TableOfContents extends Zend_View_Helper_Abstract
{
    protected $_baseUrl = null;
    
    public function tableOfContents(HumanHelp_Model_Book $book, HumanHelp_Model_Page $page = null)
    {
        $this->_baseUrl = $this->view->baseUrl . '/' . $book->getName() . '/';
        
        $html = '<ul class="toc">' . $this->_getSubTree($book->getToc()) . '</ul>';
        return $html;
    }
    
    protected function _getSubTree(SimpleXMLIterator $subTree)
    {
        $html = '';
        foreach($subTree as $tocItem) {
            switch($tocItem->getName()) {
                case 'toc':
                    $html .= $this->_getSubTree($tocItem);
                    break;
                    
                case 'folder':
                    $html .= '<li class="toc-folder">' . htmlspecialchars($tocItem->title) . 
                        '<ul>' . $this->_getSubTree($tocItem) . "</ul>\n";
                    break;
                    
                case 'page':
                    $html .= '<li class="toc-page"><a href="' . $this->_baseUrl . $tocItem['href'] . '">' . 
                        htmlspecialchars($tocItem) . "</a></li>\n";
                    break;
            }
        }
        
        return $html;
    }
}