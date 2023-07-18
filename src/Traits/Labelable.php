<?php

namespace FilippoToso\LaravelHelpers\Traits;

use Exception;

trait Labelable
{
    public function getAttribute($key)
    {
        if (preg_match('#^(.*)_label$#si', $key, $matches)) {
            $field = $matches[1];
            if (isset($this->labels[$field]) && (array_key_exists($field, $this->attributes) || in_array($field, $this->appends))) {
                $label = $this->$field;
                if (isset($this->labels[$field][$label])) {
                    return $this->labels[$field][$label];
                }
                throw new Exception(sprintf('Invalid label %s for field %s', $label, $field));
            }
        }

        return parent::getAttribute($key);
    }
}
