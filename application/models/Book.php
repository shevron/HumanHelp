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
        $contentFile = $this->_bookDataPath . $pageName;
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
        
        $this->_filterContentBody($contentBody, $contentXml);
        
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
     * Pass the content body through some filters
     * 
     * @todo Make these filters pluggable
     * 
     * @param DOMElement $body
     */
    protected function _filterContentBody(DOMElement $body, DOMDocument $doc)
    {
        // Fix all <img> tags
        $xpath = new DOMXPath($doc);
        $xpath->registerNamespace('h', 'http://www.w3.org/1999/xhtml');
        $images = $xpath->query('//h:img[@src] | //h:script[@src]');
        foreach($images as $imgTag) {
            $src = $imgTag->getAttribute('src');
            if (! preg_match('|^https?://|', $src)) {
                $imgTag->setAttribute('src', '../media/' . $this->_bookName . '/' . $src);
            }
        }
        
        // Fix all url references in inline style attributes
        $hasStyle = $xpath->query('//h:*[contains(@style, "url")]');
        foreach ($hasStyle as $hs) {
            $style = $hs->getAttribute('style');
            $style = preg_replace('/url\((.+?)\)/', 'url(../media/' . $this->_bookName . '/\1)', $style);
            $hs->setAttribute('style', $style); 
        }
        
        // Fix all background references
        $hasbg = $xpath->query('//h:*[@background]');
        foreach ($hasbg as $element) {
            $bg = $element->getAttribute('background');
            if (! preg_match('|^https?://|', $bg)) {
                $element->setAttribute('background', '../media/' . $this->_bookName . '/' . $bg);
            } 
        }
        
        // Fix poopup URLs
        $popupHrefs = $xpath->query('//h:a[starts-with(@href, "javascript:BSSCPopup(")]');
        foreach ($popupHrefs as $element) {
            $href = $element->getAttribute('href');
            $href = preg_replace('/([\'"]\);)$/', '?layout=contentOnly\1', $href);
            $element->setAttribute('href', $href);
        }
    }
}