<?php

namespace Filaio\Stream;

class MemoryStream extends Stream
{
    /**
     * Set stream wrapper type.
     *
     * @return void
     */
    public function setWrapper(): void
    {
        $this->wrapper = 'php://temp';
    }
}
