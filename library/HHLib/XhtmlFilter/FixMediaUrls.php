<?php

class HHLib_XhtmlFilter_FixMediaUrls extends HHLib_XhtmlFilter_Abstract
{
    public function filter(DOMElement $element)
    {
        $xpath = $this->_getXpath();
        $mediaUrl = '../media/' . urlencode($this->_config['bookName']) . '/';
        
        // Fix all <img> and <script> tags
        $images = $xpath->query('//h:img[@src] | //h:script[@src]');
        foreach($images as $imgTag) {
            $src = $imgTag->getAttribute('src');
            if (! preg_match('|^https?://|', $src)) {
                $imgTag->setAttribute('src', $mediaUrl . $src);
            }
        }
        
        // Fix all url references in inline style attributes
        $hasStyle = $xpath->query('//h:*[contains(@style, "url")]');
        foreach ($hasStyle as $hs) {
            $style = $hs->getAttribute('style');
            $style = preg_replace('/url\((.+?)\)/', 'url(' . $mediaUrl . '\1)', $style);
            $hs->setAttribute('style', $style); 
        }
        
        // Fix all background references
        $hasbg = $xpath->query('//h:*[@background]');
        foreach ($hasbg as $element) {
            $bg = $element->getAttribute('background');
            if (! preg_match('|^https?://|', $bg)) {
                $element->setAttribute('background', $mediaUrl . $bg);
            } 
        }
        
    }
}