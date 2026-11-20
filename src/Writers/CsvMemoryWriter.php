<?php

declare(strict_types=1);

namespace Performing\FeedBuilder\Writers;

class CsvMemoryWriter extends CsvStreamWriter
{
    public function __construct()
    {
        parent::__construct(fopen('php://memory', 'w+'));
    }

    public function save(): ?string
    {
        rewind($this->stream);

        return (string) stream_get_contents($this->stream);
    }
}
