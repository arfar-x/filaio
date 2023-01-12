<?php

namespace Filaio\Stream;

use Filaio\Contracts\StreamInterface;

class FileStream implements StreamInterface
{
    const STREAM_WRAPPER = 'filaio-file';

    /**
     * Get stream wrapper.
     *
     * @return string
     */
    public function getStreamWrapper(): string
    {
        return self::STREAM_WRAPPER;
    }
}
