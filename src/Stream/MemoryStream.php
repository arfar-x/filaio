<?php

namespace Filaio\Stream;

class MemoryStream extends Stream
{
    /**
     * Set stream wrapper type.
     *
     * @return MemoryStream
     */
    public function setWrapper(): static
    {
        $this->wrapper = 'php://temp';

        return $this;
    }
}
