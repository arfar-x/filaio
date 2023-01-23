<?php

namespace Filaio\Stream;

class FileStream extends Stream
{
    /**
     * Set stream wrapper type.
     *
     * @return FileStream
     */
    public function setWrapper(): static
    {
        $this->wrapper = 'file://';

        return $this;
    }
}
