<?php

namespace Filaio\Contracts;

use Filaio\Content\Content;

interface ResourceInterface
{
    /**
     * Determine if the file exists.
     *
     * @return bool
     */
    public function exists(): bool;

    /**
     * Get content instance.
     *
     * @return Content
     */
    public function getContent(): Content;

    /**
     * Set content value.
     *
     * @param $value
     * @return Content
     */
    public function setContent($value): Content;

    /**
     * Create a new factory instance for stream.
     *
     * @return StreamInterface
     */
    public function streamFactory(): StreamInterface;
}
