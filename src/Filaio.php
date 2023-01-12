<?php

namespace Filaio;

use Exception;
use Filaio\Content\Content;
use Filaio\Contracts\ResourceInterface;
use Filaio\Stream\Factory;

class Filaio
{
    /**
     * The resource instance.
     *
     * @var ResourceInterface
     */
    protected ResourceInterface $resource;

    /**
     * The stream factory instance.
     *
     * @var Factory
     */
    protected Factory $streamFactory;

    /**
     * Create an instance with file resource.
     *
     * @param Content|string $path
     * @param bool $exists
     * @return static
     * @throws Exception
     */
    public static function file(Content|string $path, bool $exists = true): static
    {
        return new static(new File($path, $exists));
    }

    /**
     * Create an instance of resource.
     *
     * @param ResourceInterface $resource
     */
    public function __construct(ResourceInterface $resource)
    {
        $this->resource = $resource;

        // We need to instantiate steam factory here to manage hierarchical.
        $this->streamFactory = new Factory($this->resource->streamFactory());
    }

    /**
     * Get / set the resource content.
     *
     * @param null $value
     * @return Content
     */
    public function content($value = null): Content
    {
        if ($value)
            return $this->resource->setContent($value);

        return $this->resource->getContent();
    }

    /**
     * Get stream factory instance.
     *
     * @return Factory
     */
    public function stream(): Factory
    {
        return $this->streamFactory;
    }
}
