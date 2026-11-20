<?php

namespace Performing\FeedBuilder\Writers;

interface XmlWriterInterface
{
    public function startElement(string $name): void;

    public function writeElement(string $name, string $content): void;

    public function endElement(): void;

    public function save(): ?string;
}
