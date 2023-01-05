<?php

namespace Filaio\Contracts;

use Filaio\Content\Content;

interface ResourceInterface
{
    public function getContent(): Content;

    public function setContent($value): Content;

    // public function getStream(): Stream;
}
