<?php

declare(strict_types=1);

namespace Performing\FeedBuilder\Writers;

use XMLWriter;

class StreamWriter implements Writer
{
    private XMLWriter $xml;
    private string $filename;

    public function __construct($stream)
    {
        if (!is_resource($stream)) {
            throw new \InvalidArgumentException('Invalid stream resource');
        }

        $this->xml = XMLWriter::toStream($stream);
        $this->xml->startDocument('1.0', 'UTF-8');
        $this->xml->setIndent(true);
        $this->xml->setIndentString('  ');

        $this->xml->startElement('rss');
        $this->xml->writeAttribute('version', '2.0');
        $this->xml->writeAttribute('xmlns:g', 'http://base.google.com/ns/1.0');
        $this->xml->writeAttribute('xmlns:custom', 'http://example.com/custom');

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
        $this->xml->flush();

        return null;
    }
}
