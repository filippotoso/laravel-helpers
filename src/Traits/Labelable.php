<?php

namespace FilippoToso\LaravelHelpers\Traits;

use Exception;

trait Labelable
{

    public function __get($name)
    {
        if (preg_match('#^(.*)_label$#si', $name, $matches)) {
            $field = $matches[1];
            if (isset($this->labels[$field]) && array_key_exists($field, $this->attributes)) {
                $label = $this->attributes[$field];
                if (isset($this->labels[$field][$label])) {
                    return $this->labels[$field][$label];
                }
                throw new Exception(sprintf('Invalid label %s for field %s', $label, $field));
            }
        }

        return parent::__get($name);
    }

}
