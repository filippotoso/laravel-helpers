<?php

namespace FilippoToso\LaravelHelpers\UI;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\MessageBag;

class Field
{

    protected $data = [
        'id' => '',
        'required' => false,
        'disabled' => false,
        'readonly' => false,
        'title' => '',
        'label' => '',
        'name' => '',
        'value' => '',
        'checked' => null,
        'placeholder' => '',
        'type' => 'text',
        'description' => null,

        'values' => [],

        'class' => '',
        'icon' => '',
        'rows' => null,
        'cols' => null,

        'attributes' => '',
    ];

    public function __construct($data)
    {
        $this->data = array_merge($this->data, $data);
    }

    public function id($index = null)
    {
        if (is_null($index)) {
            return $this->name();
        }
        return sprintf('%s-%s', $this->name(), str_slug($index));
    }

    public function name()
    {
        return trim(str_replace(['[', ']'], ['.', ''], $this->name), ' .');
    }

    public function old($value = null)
    {
        $name = $this->name();
        $value = is_null($value) ? $this->value : $value;

        return old($name, $value);
    }

    public function error($default = false)
    {

        $errors = Session::get('errors', new MessageBag());

        $name = $this->name();

        if ($errors->has($name)) {
            if ($default === false) {
                return $errors->first($name);
            }
            return $default;
        }

        return false;
    }

    public function checked($default = false)
    {
        $old = $this->old($default);

        $checked = false;

        if (!is_null($this->checked)) {
            $checked = $this->checked;
        } elseif (is_array($old)) {
            $checked = in_array($this->value, $old);
        } elseif (is_bool($this->value)) {
            $checked = $this->value;
        } elseif ($old == $this->value) {
            $checked = true;
        } elseif (is_null($old)) {
            $checked = $default;
        }

        return $checked ? 'checked' : '';
    }

    public function selected($value)
    {
        $old = $this->old();
        return ($value == $old) ? 'selected' : '';
    }

    public function required()
    {
        return $this->required ? 'required' : '';
    }

    public function readonly()
    {
        return $this->readonly ? 'readonly' : '';
    }

    public function disabled()
    {
        return $this->disabled ? 'disabled' : '';
    }

    public function rows()
    {
        return $this->rows ? sprintf('rows="%s"', $this->rows) : '';
    }

    public function cols()
    {
        return $this->cols ? sprintf('cols="%s"', $this->cols) : '';
    }

    public function __set(string $name, $value)
    {
        if (array_key_exists($name, $this->data)) {
            $this->data[$name] = $value;
        } else {
            trigger_error(sprintf('Undefined property via __get(): %s in %s class', $name, __class__), E_USER_NOTICE);
        }
    }

    public function __get(string $name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
        trigger_error(sprintf('Undefined property via __get(): %s in %s class', $name, __class__), E_USER_NOTICE);
        return null;
    }

    public function has(string $name)
    {
        return array_key_exists($name, $this->data);
    }
}
