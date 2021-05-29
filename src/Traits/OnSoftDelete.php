<?php

namespace FilippoToso\LaravelHelpers\Traits;

trait OnSoftDelete
{
    public static function bootOnSoftDelete()
    {
        static::deleting(function ($resource) {
            foreach (static::$relationships_to_delete as $relation) {
                foreach ($resource->{$relation}()->get() as $item) {
                    $item->delete();
                }
            }
        });

        static::restoring(function ($resource) {
            foreach (static::$relationships_to_delete as $relation) {
                foreach ($resource->{$relation}()->get() as $item) {
                    $item->withTrashed()->restore();
                }
            }
        });
    }
}
