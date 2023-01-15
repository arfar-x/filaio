<?php

namespace Filaio\Stream;

class FileStream extends Stream
{
    /**
     * Set stream wrapper type.
     *
     * @return void
     */
    public function setWrapper(): void
    {
        $this->wrapper = 'file://';
    }
}
