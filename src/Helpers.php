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

/**
 * Return the file size in human readable format
 *
 * @param  integer $bytes
 * @param  integer $decimals
 * @param  string|null $decimalSeparator
 * @param  string|null $thousandSeparator
 *
 * @return string
 */
function human_filesize($bytes, $decimals = 2, $decimalSeparator = null, $thousandSeparator = null)
{
    $decimalSeparator = $decimalSeparator ?? (localeconv()['decimal_point'] ?? '.');
    $thousandSeparator = $thousandSeparator ?? (localeconv()['thousands_sep'] ?? ',');
    $size = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $factor = min(floor((strlen($bytes) - 1) / 3), count($size) - 1);
    return number_format($bytes / pow(1024, $factor), $decimals, $decimalSeparator, $thousandSeparator) . $size[$factor];
}