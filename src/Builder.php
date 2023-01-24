<?php

namespace Filaio;

use Filaio\Stream\Factory;
use Filaio\Stream\Stream;

class Builder
{
    /**
     * The stream factory instance.
     *
     * @var Factory
     */
    protected Factory $factory;

    /**
     * The file-stream instance for better accessibility.
     *
     * @var Stream
     */
    protected Stream $stream;

    /**
     * @param Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
        $this->stream = $factory->getStream();
    }
}
