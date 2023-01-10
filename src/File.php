<?php declare(strict_types=1);

namespace Filaio;

use Exception;
use Filaio\Content\Content;
use Filaio\Contracts\ResourceInterface;
use SplFileInfo;

class File extends SplFileInfo implements ResourceInterface
{
    /**
     * Indicates if the file exists.
     *
     * @var bool
     */
    public bool $exists = false;

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
     *
     * @throws Exception
     */
    public function __construct(Content|string $entity, bool $exists = true)
    {
        $this->exists = $exists;

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
    public function resolve(string $path): bool
    {
        if (!is_file($path))
            throw new Exception("$path is not recognized as a file.");

        if ($this->exists = file_exists($path))
            $this->content = new Content(file_get_contents($this->path));

        return $this->exists;
    }

    /**
     * @return Content
     */
    public function getContent(): Content
    {
        return $this->content;
    }

    /**
     * @param $value
     * @return Content
     */
    public function setContent($value): Content
    {
        return $this->content->set($value);
    }
}
