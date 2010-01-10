<?php

class HHLib_XhtmlFilter_FixBSSCPopupUrls extends HHLib_XhtmlFilter_Abstract
{
    public function filter(DOMElement $bodyPart)
    {
        // Fix popup URLs
        $popupHrefs = $this->_getXpath()->query('//h:a[starts-with(@href, "javascript:BSSCPopup(")]');
        
        foreach ($popupHrefs as $element) {
            $href = $element->getAttribute('href');
            $href = preg_replace('/([\'"]\);)$/', '?layout=contentOnly\1', $href);
            $element->setAttribute('href', $href);
        }
    }
}