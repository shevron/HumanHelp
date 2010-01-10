<?php

class HumanHelp_Model_Book
{
    /**
     * Book name
     * 
     * @var string
     */
    protected $_bookName = null;
    
    /**
     * Book data path
     * 
     * @var string
     */
    protected $_bookDataPath = null;
    
    /**
     * Book TOC XML object
     * 
     * @var SimpleXmlElement
     */
    protected $_bookXml = null;
    
    public function __construct($bookName, $dataPath = null)
    {
        $this->_bookName = $bookName;
        if ($dataPath) {
            $this->_bookDataPath = rtrim($dataPath, DIRECTORY_SEPARATOR) . 
                DIRECTORY_SEPARATOR . $bookName . DIRECTORY_SEPARATOR;
        } else {
            $this->_bookDataPath = rtrim(dirname(APPLICATION_PATH), DIRECTORY_SEPARATOR) .
                DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . $bookName . DIRECTORY_SEPARATOR;
        }
    }
    
    /**
     * Get a specific page for this book
     * 
     * @param string $pageName
     * @return HumanHelp_Model_Page
     */
    public function getPage($pageName)
    {
        $page = new HumanHelp_Model_Page($pageName);
        $page->setBook($this);
        
        // Load page content
        $contentFile = $this->_bookDataPath . 'content' . DIRECTORY_SEPARATOR . $pageName;
        if (! is_readable($contentFile)) {
            throw new ErrorException("Page $pageName does not exist $contentFile");
        }
        
        $contentXml = new DOMDocument();
        if (! ($contentXml->load($contentFile))) {
            throw new ErrorException("Unable to load content file $contentFile");
        }
        
        $contentBody = $contentXml->getElementsByTagName('body');
        if ($contentBody->length != 1) {
            throw new ErrorException("Expecting exactly 1 body element, found {$contentBody->length}");
        }
        $contentBody = $contentBody->item(0);
        
        // Process document through XHTML filters
        foreach($this->_getXhtmlFilters() as $filter) { /* @var $filter HHLib_XhtmlFilter_Abstract */
            $filter->setDomDocument($contentXml);
            $filter->filter($contentBody);
        }
        
        $content = '';
        foreach($contentBody->childNodes as $bodyChild) { /* @var $bodyChild DOMNode */
            $content .= $contentXml->saveXML($bodyChild);
        }
        $page->setContent($content);
        
        // Set page title
        $contentXml = simplexml_import_dom($contentXml);
        $title = (string) $contentXml->head->title;
        $page->setTitle($title);
        
        return $page;
    }
    
    /**
     * Get the default page for this book
     * 
     * @return HumanHelp_Model_Page
     */
    public function getDefaultPage()
    {
        $this->_lazyLoadBookXml();
        
        // Find the first <page> element
        foreach(new RecursiveIteratorIterator($this->_bookXml) as $element) {
            if ($element->getName() == 'page' && isset($element['href'])) {
                return $this->getPage((string) $element['href']);
            }
        }
        
        throw new ErrorException("Book has no default page");
    }
    
    /**
     * Get the SimpleXmlIterator representing the TOC
     * 
     * @return SimpleXmlIterator
     */
    public function getToc()
    {
        $this->_lazyLoadBookXml();
        return $this->_bookXml->toc;
    }
    
    /**
     * Get the book name (canonical name, not title)
     * 
     * @return string
     */
    public function getName()
    {
        return $this->_bookName;
    }
    
    /**
     * Get the title of this book from the XML file
     * 
     * @return string
     */
    public function getTitle()
    {
        $this->_lazyLoadBookXml();
        return (string) $this->_bookXml->title;
    }
    
    /**
     * Preform lazy loading of the book XML file, if not done yet
     * 
     * @return SimpleXmlElement
     */
    protected function _lazyLoadBookXml()
    {
        if (! $this->_bookXml) {
            if (! ($this->_bookXml = simplexml_load_file($this->_bookDataPath . $this->_bookName . '.xml', 'SimpleXMLIterator'))) {
                throw new ErrorException("Unable to load book XML file for book $this->_bookName");
            }
        }
        
        return $this->_bookXml;
    }
    
    /**
     * Get all page filter objects registered for this book
     * 
     * @todo   Read filter specific configuration from XML and pass to filter
     * 
     * @return array
     */
    protected function _getXhtmlFilters()
    {
        $this->_lazyLoadBookXml();
        $filters = array();
        
        $globalConfig = array(
            'bookName' => $this->_bookName
        );
        
        foreach($this->_bookXml->pageFilters->filter as $filterClass) {
            $filterClass = (string) $filterClass['class']; 
            Zend_Loader::loadClass($filterClass);
            $filter = new $filterClass($globalConfig); /* @var $filter HHLib_XhtmlFilter_Abstract */
            $filters[] = $filter;
        }
        
        return $filters;
    }
}