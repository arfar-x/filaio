<?php

namespace Filaio\Stream;

use Exception;

abstract class Stream
{
    /**
     * The stream wrapper that underlying filestream is working with.
     *
     * @var string
     */
    protected string $wrapper;

    /**
     * Stream resource initialized in constructor.
     *
     * @var false|resource
     */
    protected $stream;

    /**
     * Stream resource handler mode.
     *
     * @var array|string[]
     */
    protected array $modes = [
        'read_beginning' => 'r',
        'read_write' => 'r+',
        'write_create' => 'w',
        'read_write_create' => 'w+',
        'write_end_create' => 'a',
        'read_write_end_create' => 'a+',
        'write_beginning_create_error_on_exist' => 'x',
        'read_write_beginning_create_error_on_exist' => 'x+',
    ];

    /**
     * The position of stream resource pointer.
     *
     * @var int
     */
    protected int $cursor;

    /**
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->setWrapper()
            ->openStream($path);
    }

    /**
     * Open and set stream resource.
     *
     * @param string $path
     * @return $this
     */
    public function openStream(string $path): static
    {
        $this->stream = fopen($this->getWrapperFullForm() . '/' . $path, $this->getMode('read_beginning'));

        return $this;
    }

    /**
     * Get stream wrapper in full form.
     *
     * @return string
     */
    public function getWrapperFullForm(): string
    {
        return $this->wrapper;
    }

    /**
     * Get stream mode type to handle stream resource.
     *
     * @param string $key
     * @return string
     */
    public function getMode(string $key): string
    {
        return $this->modes[$key];
    }

    /**
     * Get stream wrapper.
     *
     * @return string
     */
    public function getWrapper(): string
    {
        return str_replace('://', '', $this->wrapper);
    }

    /**
     * Set stream wrapper type.
     *
     * @return Stream
     */
    abstract public function setWrapper(): static;

    /**
     * Read the content with specified length from stream.
     *
     * @param int $length
     * @return bool|string
     */
    public function read(int $length): bool|string
    {
        return fread($this->stream, $length);
    }

    /**
     * Read only one line from stream.
     *
     * @return bool|string
     */
    public function readLine(): bool|string
    {
        $this->moveCursor(1, 'current');
        return fgets($this->stream);
    }

    /**
     * Write data to content.
     *
     * @param mixed $data
     * @param int $position
     * @param string $mode
     * @return string|int
     * @throws Exception
     */
    public function write(mixed $data, int $position = 0, string $mode = 'override'): string|int
    {
        /**
         * TODO Modes:
         *  plus: add given data with the beginning point of $position and before the next byte
         *   e.g. position = 10 => $data is added to 11 and previous 11 byte is size($data)+1
         *  override: override the content of given position until given data ends (like INSERT key mode)
         *  before : opposite of plus
         */

        switch ($mode) {
            case 'plus':
                $this->moveCursor($position, 'set');
                // TODO Add padding with the size of $data to next byte/character
                return fwrite($this->stream, $data);
            case 'override':
                $this->setEmpty();
                return fwrite($this->stream, $data);
            case 'before':
                // TODO We can use reverse of $data size (negative $data size)
                return fwrite($this->stream, $data);
            default:
                throw new Exception("Unknown write mode {$mode}.");
        }
    }

    /**
     * Get the position of the cursor.
     *
     * @return int
     */
    public function getPosition(): int
    {
        return $this->cursor;
    }

    /**
     * Set the stream resource pointer (cursor) to beginning.
     *
     * @return bool
     */
    public function setToBeginning(): bool
    {
        if (rewind($this->stream)) {
            $this->cursor = 0;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Set the stream content to empty.
     *
     * @return bool
     */
    public function setEmpty(): bool
    {
        return ftruncate($this->stream, $this->getSize());
    }

    /**
     * Get stream content size.
     *
     * @return int
     */
    public function getSize(): int
    {
        return $this->info()['size'];
    }

    /**
     * Get information about stream resource.
     *
     * @return bool|array
     */
    public function info(): bool|array
    {
        return fstat($this->stream);
    }

    /**
     * Check if the cursor is at the end of stream data.
     *
     * @return bool
     */
    public function isEnd(): bool
    {
        return $this->cursor >= $this->getSize();
    }

    /**
     * Move the cursor to/by given offset.
     * Modes operations are:
     *      set: sets the cursor to given offset bytes.
     *      current: sets the cursor to current location plus given offset.
     *      end: sets the cursor to end-of-file plus given offset.
     *
     * @param int $offset
     * @param string|int $mode
     * @return bool|int
     */
    public function moveCursor(int $offset, string|int $mode): bool|int
    {
        if (!is_integer($mode))
            $mode = match ($mode) {
                'set' => SEEK_SET,
                'current' => SEEK_CUR,
                'end' => SEEK_END
            };

        if (fseek($this->stream, $offset, $mode)) {
            return $this->cursor = $this->getRawPosition();
        }

        return false;
    }

    /**
     * Get real position of stream resource pointer by evaluating the resource.
     *
     * @return bool|int
     */
    public function getRawPosition(): bool|int
    {
        return ftell($this->stream);
    }

    /**
     * Set cursor to absolute position.
     * Get cursor position.
     *
     * @param int|null $position
     * @return bool|int
     */
    public function cursor(int $position = null): bool|int
    {
        if ($position)
            return (bool)$this->moveCursor($position, 'set');
        elseif ($position == 0)
            return $this->setToBeginning();
        else
            return $this->getPosition();
    }
}
