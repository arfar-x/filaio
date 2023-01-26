<?php

namespace Filaio\Content;

use Filaio\Builder;

class Chunk
{
    /**
     * Default each chunk size.
     *
     * @var int
     */
    protected int $chunkSize;

    /**
     * The builder instance.
     *
     * @var Builder
     */
    protected Builder $builder;

    /**
     * @param Builder $builder
     * @param int $defaultChunkSize
     */
    public function __construct(Builder $builder, int $defaultChunkSize)
    {
        $this->builder = $builder;
        $this->chunkSize = $defaultChunkSize;
    }

    /**
     * Map the content by given chunk size.
     *
     * @param string|Content $content
     * @param $chunkSize
     * @param callable $callable
     * @return false|Content
     */
    public function map(string|Content $content, $chunkSize, callable $callable): false|Content
    {
        for ($part = 0 ; $part < $this->divide($content, $chunkSize) ; ++$part) {
            $content = $callable($this->builder, (string)$content);
        }

        return $content;
    }

    /**
     * Divide the content by given each chunk size (in bytes).
     *
     * @param string|Content $content
     * @param int $chunkSize
     * @return int
     */
    public function divide(string|Content $content, int $chunkSize): int
    {
        // TODO Reconsider the necessity of $content.
        return ceil($this->builder->size() / $chunkSize);
    }

    /**
     * Divide the content to the number of parts.
     *
     * @param string|Content $content
     * @param int $number
     * @return int
     */
    public function divideTo(string|Content $content, int $number): int
    {
        $content = (string)$content;

        $chunkSize = strlen($content) / $number;

        return $this->divide($content, $chunkSize);
    }
}
