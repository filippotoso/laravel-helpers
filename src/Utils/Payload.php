<?php

namespace FilippoToso\LaravelHelpers\Utils;

use FilippoToso\LaravelHelpers\Utils\Exceptions\InvalidPropertyException;

class Payload
{

    protected $properties = [];

    protected $data = [];

    protected $defaults = [];

    public function __construct($payload = [])
    {
        foreach ($this->defaults as $key => $value) {
            $this->data[$key] = $value;
        }

        $this->properties = array_merge($this->properties, array_keys($this->defaults));

        foreach ($this->properties as $key) {
            if (array_key_exists($key, $payload)) {
                $this->data[$key] = $payload[$key];
            }
        }
    }

    public function __set($name, $value)
    {
        if (!in_array($name, $this->properties)) {
            throw new InvalidPropertyException(sprintf('Invalid %s parameter', $name));
        }
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        if (!in_array($name, $this->properties)) {
            throw new InvalidPropertyException(sprintf('Invalid %s parameter', $name));
        }
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }

    public static function create($payload = [])
    {
        return new static($payload);
    }

}
