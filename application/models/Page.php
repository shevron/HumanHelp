<?php

class HumanHelp_Model_Page
{
    /**
     * Book this page belongs to
     * 
     * @var HumanHelp_Model_Book
     */
    protected $_book = null;
    
    /**
     * Name of this page
     * 
     * @var string
     */
    protected $_pageName = null;
    
    /**
     * Content
     * 
     * @var string
     */
    protected $_content = null;

    /**
     * JavaScript references
     * 
     * @var array
     */
    protected $_jsRefs = array();
    
    public function __construct($pageName)
    {
        $this->_pageName = $pageName;
    }
    
    /**
     * Set the book this page belongs to 
     * 
     * @param HumanHelp_Model_Book $book
     */
    public function setBook(HumanHelp_Model_Book $book)
    {
        $this->_book = $book;
    }

    /**
     * Set the page's title 
     * 
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->_title = $title;
    }
    
    /**
     * Set the content of this page
     * 
     * @param string $html
     */
    public function setContent($content)
    {
        $this->_content = $content;
    }

    /**
     * Get the name of this page
     * 
     * @return string
     */
    public function getName()
    {
        return $this->_pageName;
    }
    
    /**
     * Get the book this page belongs to
     * 
     * @return HumanHelp_Model_Book
     */
    public function getBook()
    {
        return $this->_book;
    }
    
    /**
     * Get the content of this page
     * 
     * @return string
     */
    public function getContent()
    {
        return $this->_content;
    }
    
    /**
     * Get the page's title
     * 
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * Get comments for this page
     * 
     * @param  boolean $approvedOnly
     * @return array
     */
    public function getComments($approvedOnly = true)
    {
        return HumanHelp_Model_Comment::getCommentsForPage(
            $this->_book->getName(), $this->_pageName, $approvedOnly);
    }
}
