<?php

if (! isset($_SERVER['argv'][1])) {
    die("You must specify an input file!\n");
}
$inFile = $_SERVER['argv'][1];

$writer = new XMLWriter();
$writer->openUri('php://stdout');
$writer->setIndent(true);
$writer->setIndentString("  ");

$converter = new HumanHelp_Xml_Converter_RobohelpXhtml($inFile);
$converter->setWriter($writer);

$converter->convert();

class HumanHelp_Xml_Converter_RobohelpXhtml
{
    /**
     * Reader object used to parse the RoboHelp XML file
     * 
     * @var XMLReader
     */
    protected $_reader = null;
    
    /**
     * Writer object used to write the HumanHelp TOC file
     * 
     * @var XMLWriter
     */
    protected $_writer = null;
    
    public function __construct($inFile)
    {
        $this->_reader = new XMLReader();
        $this->_reader->open($inFile);
        
        $this->_reader->read();
        
        // Check that we are in the right place
        while ($this->_reader->read()) 
            if ($this->_reader->nodeType == XMLReader::ELEMENT) break;
        
        if (! ($this->_reader->nodeType == XMLReader::ELEMENT && 
               $this->_reader->name == 'roboml_toc')) {

            throw new ErrorException("Unexpected XML node: {$this->_reader->name}");
        }
    }
    
    public function setWriter(XMLWriter $writer)
    {
        $this->_writer = $writer;
    }
    
    public function convert()
    {
        if (! $this->_writer) { 
            $this->_writer = new XMLWriter();
            $this->_writer->open('php://stdout');
        }
        
        $this->_convertRoot();
    }
    
    protected function _convertRoot()
    {
        // Find first <book> element
        while (! ($this->_reader->nodeType == XMLReader::ELEMENT && 
                  $this->_reader->name == 'book'))
            $this->_reader->read();
            
        if (! ($this->_reader->nodeType == XMLReader::ELEMENT && 
               $this->_reader->name == 'book')) {
            throw new ErrorException("Unable to find root book element");
        }
        
        // Find the title attribute
        $bookTitle = $this->_reader->getAttribute('title');
        if (! $bookTitle) {
            throw new ErrorException("Unable to find book title attribute");
        }
        
        // Start XML and write root element
        $this->_writer->startDocument('1.0', 'UTF-8');
        $this->_writer->startElement('book');
        $this->_writer->writeAttribute('xmlns', 'http://arr.gr/humanhelp/book');
        $this->_writer->writeAttribute('timestamp', time());
        
        // Write title
        $this->_writer->writeElement('title', $bookTitle);
        
        // Write default pageFilters
        $this->_writer->startElement('pageFilters');
        
        $this->_writer->startElement('filter');
        $this->_writer->writeAttribute('class', 'HHLib_XhtmlFilter_FixMediaUrls');
        $this->_writer->endElement();
        
        $this->_writer->startElement('filter');
        $this->_writer->writeAttribute('class', 'HHLib_XhtmlFilter_FixBSSCPopupUrls');
        $this->_writer->endElement();
        
        $this->_writer->endElement();
        
        // Start creating the the TOC
        $this->_writer->startElement('toc');
        while ($this->_reader->read()) {
            if ($this->_reader->nodeType == XMLReader::ELEMENT) {
                switch ($this->_reader->name) {
                    case 'page':
                        $this->_convertPage();
                        break;
                        
                    case 'book':
                        $this->_convertBook();
                        break;
                }
            }
        }
        
        $this->_writer->endElement(); // End of <toc> element
        $this->_writer->endElement(); // End of root <book> element
        $this->_writer->endDocument();
        $this->_writer->flush();
    }
    
    protected function _convertPage()
    {
        if (! ($this->_reader->name == 'page' && $this->_reader->nodeType == XMLReader::ELEMENT)) {
            throw new ErrorException("Unexpected XML reader position, expecting a page element");
        }
        
        $title = $this->_reader->getAttribute('title');
        $href  = $this->_reader->getAttribute('url');
        
        $this->_writer->startElement('page');
        $this->_writer->writeAttribute('href', $href);
        $this->_writer->text($title);
        $this->_writer->endElement();
    }
    
    protected function _convertBook()
    {
        if (! ($this->_reader->name == 'book' && $this->_reader->nodeType == XMLReader::ELEMENT)) {
            throw new ErrorException("Unexpected XML reader position, expecting a book element");
        }
        
        $title = $this->_reader->getAttribute('title');
        
        $this->_writer->startElement('folder');
        $this->_writer->writeElement('title', $title);
        
        while ($this->_reader->read()) {
            if ($this->_reader->nodeType == XMLReader::END_ELEMENT && $this->_reader->name == 'book') {
                break; // Done reading book element
            }
            
            if ($this->_reader->nodeType == XMLReader::ELEMENT) {
                switch ($this->_reader->name) {
                    case 'page':
                        $this->_convertPage();
                        break;
                        
                    case 'book':
                        $this->_convertBook();
                        break;
                }
            }
        }
        
        $this->_writer->endElement(); // End <folder> element
    }
}