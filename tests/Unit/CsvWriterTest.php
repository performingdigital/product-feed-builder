<?php

declare(strict_types=1);

use Performing\FeedBuilder\Writers\CsvMemoryWriter;
use Performing\FeedBuilder\Writers\CsvStreamWriter;

test('CsvMemoryWriter writes the header from the keys of the first row', function () {
    $writer = new CsvMemoryWriter();
    $writer->writeRow(['id' => '1', 'title' => 'First']);
    $writer->writeRow(['id' => '2', 'title' => 'Second']);

    expect($writer->save())->toBe("id,title\n1,First\n2,Second\n");
});

test('CsvMemoryWriter quotes values with commas, quotes and newlines', function () {
    $writer = new CsvMemoryWriter();
    $writer->writeRow(['title' => 'Title, with "quotes"', 'description' => "Line one\nLine two"]);

    expect($writer->save())->toBe("title,description\n\"Title, with \"\"quotes\"\"\",\"Line one\nLine two\"\n");
});

test('CsvStreamWriter writes to the stream and returns null', function () {
    $stream = fopen('php://memory', 'w+');
    $writer = new CsvStreamWriter($stream);
    $writer->writeRow(['id' => '1']);

    rewind($stream);

    expect($writer->save())->toBeNull();
    expect(stream_get_contents($stream))->toBe("id\n1\n");
});

test('CsvStreamWriter requires a stream', function () {
    expect(fn() => new CsvStreamWriter('feed.csv'))->toThrow(InvalidArgumentException::class, 'Invalid stream resource');
});
