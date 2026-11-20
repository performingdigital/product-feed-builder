<?php

declare(strict_types=1);

namespace Performing\FeedBuilder\Writers;

interface CsvWriterInterface
{
    /**
     * Write a row keyed by column name. The keys of the first row are written as the header
     *
     * @param array<string, string> $row
     */
    public function writeRow(array $row): void;

    public function save(): ?string;
}
