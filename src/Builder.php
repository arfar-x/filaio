<?php

namespace Filaio;

use Filaio\Content\Chunk;
use Filaio\Content\Content;
use Filaio\Stream\Factory;
use Filaio\Stream\Stream;

class Builder
{
    /**
     * The stream factory instance.
     *
     * @var Factory
     */
    protected Factory $factory;

    /**
     * The file-stream instance for better accessibility.
     *
     * @var Stream
     */
    protected Stream $stream;

    /**
     * The content value or instance.
     *
     * @var string|Content
     */
    protected string|Content $content;

    /**
     * The chunk instance.
     *
     * @var Chunk
     */
    protected Chunk $chunk;

    /**
     * @param Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
        $this->stream = $factory->getStream();
        $this->content = "";
        $this->chunk = new Chunk($this, 1 << 24); // Default chunk is 16777216 bits
    }

    /**
     * Get the size in bytes.
     *
     * @return int
     */
    public function size(): int
    {
        return $this->stream->getSize();
    }

    /**
     * Get chunk instance.
     *
     * @return Chunk
     */
    public function chunk(): Chunk
    {
        return $this->chunk;
    }

    /**
     * Map the content by given chunk size.
     *
     * @param $chunkSize
     * @param callable $callable
     * @return Content|bool
     */
    public function map($chunkSize, callable $callable): Content|bool
    {
        return $this->chunk->map($this->content, $chunkSize, $callable);
    }

    /**
     * Divide the content by given each chunk size (in bytes).
     *
     * @param int $chunkSize
     * @return int
     */
    public function divide(int $chunkSize): int
    {
        return $this->chunk->divide($this->content, $chunkSize);
    }

    /**
     * Divide the content to the number of parts.
     *
     * @param int $number
     * @return int
     */
    public function divideTo(int $number): int
    {
        return $this->chunk->divideTo($this->content, $number);
    }
}
