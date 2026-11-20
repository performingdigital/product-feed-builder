<?php

declare(strict_types=1);

namespace Performing\FeedBuilder\Writers;

class CsvStreamWriter implements CsvWriterInterface
{
    private bool $hasHeader = false;

    public function __construct(
        protected $stream,
    ) {
        if (!is_resource($stream)) {
            throw new \InvalidArgumentException('Invalid stream resource');
        }
    }

    public function writeRow(array $row): void
    {
        if (!$this->hasHeader) {
            $this->write(array_keys($row));
            $this->hasHeader = true;
        }

        $this->write(array_values($row));
    }

    public function save(): ?string
    {
        fflush($this->stream);

        return null;
    }

    private function write(array $values): void
    {
        if (fputcsv($this->stream, $values, ',', '"', '') === false) {
            throw new \RuntimeException('Could not write to the stream');
        }
    }
}
