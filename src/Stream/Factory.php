<?php

namespace Filaio\Stream;

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
     * Get stream instance.
     *
     * @return Stream
     */
    public function getStream(): Stream
    {
        return $this->stream;
    }
}
