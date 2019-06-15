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

function human_filesize($bytes, $decimals = 2, $decimalSeparator = null, $thousandSeparator = null) {
    $decimalSeparator = $decimalSeparator ?? (localeconv()['decimal_point'] ?? '.');
    $thousandSeparator = $thousandSeparator ?? (localeconv()['thousands_sep'] ?? ',');
    $size = array('B','KB','MB','GB','TB','PB','EB','ZB','YB');
    $factor = min(floor((strlen($bytes) - 1) / 3), count($size) - 1);
    return number_format($bytes / pow(1024, $factor), $decimals, $decimalSeparator, $thousandSeparator) . (isset($size[$factor]) ? $size[$factor] : end($size[$factor]));
}