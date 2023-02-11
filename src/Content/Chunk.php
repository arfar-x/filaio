<?php

namespace Filaio\Content;

use Exception;
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
     * @return false|string|Content
     * @throws Exception
     */
    public function map(string|Content $content, $chunkSize, callable $callable): false|string|Content
    {
        $this->loop($content, $chunkSize, $callable);

        return $content;
    }

    /**
     * Run lazy loop and do operation on it.
     *
     * @param string|Content $content
     * @param int $chunkSize
     * @param callable|null $callable
     * @param int|null $untilPartNumber
     * @return $this
     * @throws Exception
     */
    public function loop(string|Content &$content, int $chunkSize, callable $callable = null, int $untilPartNumber = null): static
    {
        if ($untilPartNumber >= 0)
            return $this->lazyLoop($content, $chunkSize, $callable, $untilPartNumber);
        else
            return $this->reverseLazyLoop($content, $chunkSize, $callable, $untilPartNumber);
    }

    /**
     * Run lazy loop from the first part to $untilPartNumber.
     *
     * @param string|Content $content
     * @param int $chunkSize
     * @param callable|null $callable
     * @param int|null $untilPartNumber
     * @return Chunk
     * @throws Exception
     */
    protected function lazyLoop(string|Content &$content, int $chunkSize, callable $callable = null, int $untilPartNumber = null): static
    {
        if (!is_null($untilPartNumber) && $untilPartNumber > 0)
            throw new Exception(sprintf('"untilPartNumber" should not be a negative number, %d given.', $untilPartNumber));

        $condition = $untilPartNumber ?? $this->divide($content, $chunkSize);
        $from = 0;
        $to = $chunkSize;

        for ($part = 0 ; $part < $condition ; ++$part) {
            $from += $chunkSize;
            $to += $chunkSize;
            $partContent = $this->builder->readBytes($from, $to);
            $callable($this->builder, $partContent);
        }

        return $this;
    }

    /**
     * Run lazy loop from the last part to given $untilPartNumber in reverse.
     *
     * @param string|Content $content
     * @param int $chunkSize
     * @param callable|null $callable
     * @param int|null $untilPartNumber
     * @return $this
     * @throws Exception
     */
    protected function reverseLazyLoop(string|Content &$content, int $chunkSize, callable $callable = null, int $untilPartNumber = null): static
    {
        if (!is_null($untilPartNumber) && $untilPartNumber >= 0)
            throw new Exception(sprintf('"untilPartNumber" should not be a positive number, %d given.', $untilPartNumber));

        $condition = abs($untilPartNumber ?? $this->divide($content, $chunkSize));
        $to = $this->builder->size();
        $from = $to - $chunkSize;

        for ($part = $condition ; $part >= 0 ; --$part) {
            $from -= $chunkSize;
            $to -= $chunkSize;
            $partContent = $this->builder->readBytes($from, $to);
            $callable($this->builder, $partContent);
        }

        return $this;
    }

    /**
     * Do operation on a specific part.
     *
     * @param string|Content $content
     * @param int $partNumber
     * @param callable $callable
     * @return Content|string
     * @throws Exception
     */
    public function onPart(string|Content $content, int $partNumber, callable $callable): Content|string
    {
        $this->loop($content, $this->chunkSize, $callable($this->builder, (string)$content), $partNumber);

        return $content;
    }

    /**
     * Set default chunk size.
     *
     * @param int $size
     * @return $this
     */
    public function setChunkSize(int $size): static
    {
        $this->chunkSize = $size;

        return $this;
    }

    /**
     * Divide the content by given each chunk size (in bytes).
     * Return the number of parts in total.
     *
     * @param string|Content $content
     * @param int $chunkSize
     * @return int
     */
    public function divide(string|Content $content, int $chunkSize): int
    {
        return ceil($this->builder->onContent($content, fn($builder) => $builder->size() / $chunkSize));
    }

    /**
     * Divide the content to the number of parts.
     * Return the size of each part.
     *
     * @param string|Content $content
     * @param int $number
     * @return int
     */
    public function divideTo(string|Content $content, int $number): int
    {
        return ceil($this->builder->onContent($content, fn($builder) => $builder->size() / $number));
    }
}
