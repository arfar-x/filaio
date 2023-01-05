<?php

namespace Filaio\Content;

class Word
{
    /**
     * @var mixed
     */
    protected mixed $value;

    /**
     * Get word value.
     *
     * @return mixed
     */
    public function get(): mixed
    {
        return $this->value;
    }

    /**
     * Set word value.
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
