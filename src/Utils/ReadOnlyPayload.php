<?php

namespace FilippoToso\LaravelHelpers\Utils;

use FilippoToso\LaravelHelpers\Utils\Exceptions\InvalidPropertyException;
use FilippoToso\LaravelHelpers\Utils\Exceptions\ReadOnlyException;

class ReadOnlyPayload extends Payload
{

    public function __set($name, $value)
    {
        throw new ReadOnlyException('This payload is read only!');
    }

}
