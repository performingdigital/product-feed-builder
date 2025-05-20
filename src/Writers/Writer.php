<?php

namespace Performing\FeedBuilder\Writers;

interface Writer
{
    public function startElement(string $name): void;

    public function writeElement(string $name, string $content): void;

    public function endElement(): void;

    public function save(): ?string;
}
