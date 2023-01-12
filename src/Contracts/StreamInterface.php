<?php

namespace Filaio\Contracts;

interface StreamInterface
{
    /**
     * Get stream wrapper.
     *
     * @return string
     */
    public function getStreamWrapper(): string;
}
