<?php

namespace FilippoToso\LaravelHelpers\Traits;

trait Versionable
{

    protected $versionableField = 'parent_id';

    /**
     * Overwrite the default update method.
     *
     * @param  array  $attributes
     * @param  array  $options
     * @return bool
     */
    public function version()
    {
        $versionableField = $this->versionableField;
        $versioned = $this->replicate();
        $versioned->$versionableField = $this->id;
        $versioned->save();
    }

    public function scopeLastVersion($query)
    {
        return $query->whereNull($this->versionableField);
    }

}

