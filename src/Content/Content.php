<?php

namespace Filaio\Content;

class Content
{
    /**
     * @var mixed
     */
    protected mixed $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Get content value.
     *
     * @return mixed
     */
    public function get(): mixed
    {
        return $this->value;
    }

    /**
     * Set content value.
     *
     * @param $value
     * @return mixed
     */
    public function set($value): static
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->get();
    }
}
