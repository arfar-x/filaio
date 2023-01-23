<?php declare(strict_types=1);

namespace Filaio;

use Exception;
use Filaio\Content\Content;
use Filaio\Contracts\ResourceInterface;
use Filaio\Stream\FileStream;
use Filaio\Stream\MemoryStream;
use Filaio\Stream\Stream;
use SplFileInfo;

class File extends SplFileInfo implements ResourceInterface
{
    /**
     * Indicates if the file exists.
     *
     * @var bool
     */
    protected bool $exists = false;

    /**
     * Indicates stream wrapper which is bound to stream factory.
     *
     * @var string
     */
    protected string $streamWrapper;

    /**
     * Indicates file path.
     *
     * @var string
     */
    protected string $path;

    /**
     * The file content instance.
     *
     * @var Content
     */
    protected Content $content;

    /**
     * Resolve the file with path or content instance.
     *
     * @param Content|string $entity
     * @param bool $exists
     * @param string $streamWrapper
     * @throws Exception
     */
    public function __construct(Content|string $entity, bool $exists = true, string $streamWrapper = 'file')
    {
        // TODO Reconsider logic for temporary (in-memory) files
        $this->exists = $exists;
        $this->streamWrapper = $streamWrapper;

        if ($entity instanceof Content) {
            $this->content = $entity;
        } else {
            $this->path = $entity;
            $this->resolve($entity);
        }

        parent::__construct($this->path);
    }

    /**
     * Resolve the file and make sure file exists.
     *
     * @param string $path
     * @return bool
     *
     * @throws Exception
     */
    protected function resolve(string $path): bool
    {
        if (!is_file($path))
            throw new Exception("$path is not recognized as a file.");

        if ($this->exists = file_exists($path))
            $this->content = new Content('file_get_contents($this->path)');

        return $this->exists;
    }

    /**
     * Determine if the file exists.
     *
     * @return bool
     */
    public function exists(): bool
    {
        return $this->exists;
    }

    /**
     * Get content instance.
     *
     * @return Content
     */
    public function getContent(): Content
    {
        return $this->content;
    }

    /**
     * Set content value.
     *
     * @param $value
     * @return Content
     */
    public function setContent($value): Content
    {
        return $this->content->set($value);
    }

    /**
     * Create a new factory instance for stream.
     *
     * @return Stream
     */
    public function streamFactory(): Stream
    {
        return $this->exists()
            ? new FileStream($this->path)
            : new MemoryStream($this->path);
    }
}
