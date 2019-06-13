<?php

/**
 * Create a Field object from the provided data
 *
 * @param  array $data
 *
 * @return Field
 */
function field(array $data)
{
    return new FilippoToso\LaravelHelpers\UI\Field($data);
}