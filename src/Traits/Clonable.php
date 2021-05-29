<?php

namespace FilippoToso\LaravelHelpers\Traits;

trait Clonable
{
    public function clone($payload = [])
    {
        $model = $this->replicate();

        foreach ($payload as $attribute => $value) {
            $model->$attribute = $value;
        }

        $model->save();

        return $model;
    }
}
