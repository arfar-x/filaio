<?php

namespace Filaio\Stream;

/**
 * Is there any way to access stream objects (FileStream or MemoryStream) through Factory object
 * from Builder (high-level string layer) ?
 *
 * In Builder.php:
 *
 * $this->factory->read(onlyLine: true)
 * instead of factory calling read from stream instance afterwards.
 */
class Factory
{
    /**
     * The stream instance.
     *
     * @var Stream
     */
    protected Stream $stream;

    /**
     * @param Stream $stream
     */
    public function __construct(Stream $stream)
    {
        $this->stream = $stream;
    }

    /**
     * @return string
     */
    public function read(): string
    {
//        return $this->stream->read();
        return 'none';
    }

    /**
     * @return string
     */
    public function write(): string
    {
//        return $this->stream->write();
        return 'none';
    }
}
