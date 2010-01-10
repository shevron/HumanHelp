<?php

abstract class HHLib_XhtmlFilter_Abstract 
{
    /**
     * Document we are filtering 
     * 
     * @var DOMDocument
     */
    protected $_domDoc = null;

    /**
     * Configuration array
     * 
     * @var array
     */
    protected $_config = array();
    
    public function __construct(array $config = array())
    {
        foreach($config as $key => $value) {
            $this->_config[$key] = $value;
        }
    }
    
    /**
     * Set the parent DOMDocument 
     * 
     * @param DOMDocument $doc
     */
    public function setDomDocument(DOMDocument $doc)
    {
        $this->_domDoc = $doc;
    }
    
    
    /**
     * Get an XPath object with the XHTML namespace already registered
     * 
     * If no $htmlNS is provided, XHTML elements prefix will be 'h:'
     * 
     * @param  string $htmlNS
     * @return DOMXpath
     */
    protected function _getXpath($htmlNS = 'h')
    {
        $xpath = new DOMXPath($this->_domDoc);
        $xpath->registerNamespace($htmlNS, 'http://www.w3.org/1999/xhtml');
        
        return $xpath;
    }
    
    /**
     * Filter the passed in DOMElement
     * 
     * @param DOMElement $element
     */
    abstract public function filter(DOMElement $element);
}