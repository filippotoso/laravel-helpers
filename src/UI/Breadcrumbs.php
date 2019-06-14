<?php

namespace FilippoToso\LaravelHelpers\UI;

class Breadcrumbs
{

    protected $data = [];

    public function set($data)
    {
        $this->data = array_slice($data, 0, null, true);
    }

    public function add($url, $label = '')
    {
        if (is_array($url)) {
            foreach ($url as $key => $value) {
                $this->data[$key] = $value;
            }
        } else {
            $this->data[$url] = $label;
        }

    }

    public function get()
    {
        return array_slice($this->data, 0, -1, true);
    }

    public function last()
    {
        return end($this->data);
    }

    public function isEmpty()
    {
        return count($this->data) == 0;
    }

    public function all()
    {
        return array_slice($this->data, 0, null, true);
    }

}

