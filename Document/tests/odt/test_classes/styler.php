<?php

class ezcDocumentOdtTestStyler implements ezcDocumentOdtStyler
{
    public $odtDocument;

    public $seenElements = [];

    public function init( DOMDocument $odtDocument )
    {
        $this->odtDocument = $odtDocument;
    }

    public function applyStyles( ezcDocumentLocateable $docBookElement, DOMElement $odtElement )
    {
        $this->seenElements[] = [$docBookElement->tagName, $odtElement->tagName];
    }
}

?>
