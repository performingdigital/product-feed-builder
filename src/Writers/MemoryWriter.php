<?php

declare(strict_types=1);

namespace Performing\FeedBuilder\Writers;

use XMLWriter;

class MemoryWriter implements Writer
{
    private XMLWriter $xml;

    public function __construct()
    {
        $this->xml = new XMLWriter();
        $this->xml->openMemory();
        $this->xml->startDocument('1.0', 'UTF-8');
        $this->xml->setIndent(true);
        $this->xml->setIndentString('  ');

        // Start the RSS root element with all required namespaces
        $this->xml->startElement('rss');
        $this->xml->writeAttribute('version', '2.0');
        $this->xml->writeAttribute('xmlns:g', 'http://base.google.com/ns/1.0');

        // Start the channel element
        $this->xml->startElement('channel');
    }

    public function startElement(string $name): void
    {
        $this->xml->startElement($name);
    }

    public function writeElement(string $name, string $content): void
    {
        $this->xml->writeElement($name, $content);
    }

    public function endElement(): void
    {
        $this->xml->endElement();
    }

    public function save(): ?string
    {
        $this->xml->endElement(); // channel
        $this->xml->endElement(); // rss
        $this->xml->endDocument();

        return $this->xml->outputMemory();
    }
}
