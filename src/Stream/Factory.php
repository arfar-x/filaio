<?php

namespace Filaio\Stream;

use Filaio\Contracts\StreamInterface;

class Factory
{
    /**
     * The stream instance.
     *
     * @var StreamInterface
     */
    protected StreamInterface $stream;

    /**
     * @param StreamInterface $stream
     */
    public function __construct(StreamInterface $stream)
    {
        $this->stream = $stream;
    }

    /**
     * Get customized stream wrapper for the stream class.
     *
     * @return string
     */
    public function getStreamWrapper(): string
    {
        return $this->stream->getStreamWrapper();
    }

    /**
     * @return void
     */
    public function read(): void
    {
        // TODO: Implement read() method.
    }

    /**
     * @return void
     */
    public function write(): void
    {
        // TODO: Implement write() method.
    }
}
