<?php

namespace FilippoToso\LaravelHelpers\Utils;

class Language
{
    public static function identify($validLanguages, $default = false, $acceptLanguage = null)
    {
        $validLanguages = is_array($validLanguages) ? array_values($validLanguages) : [$validLanguages];
        $acceptLanguage = $acceptLanguage ?? ($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? null);

        $languages = array();

        $httpLanguages = preg_split('/q=([\d\.]*)/', $acceptLanguage, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        $key = 0;
        foreach (array_reverse($httpLanguages) as $value) {
            $value = trim($value, ',; .');
            if (is_numeric($value)) {
                $key = $value;
            } else {
                $languages[$key] = array_map('trim', explode(',', $value));
            }
        }
        krsort($languages);

        $acceptedLanguages = [];
        foreach ($languages as $current) {
            $acceptedLanguages = array_merge($acceptedLanguages, $current);
        }

        foreach ($validLanguages as $validLanguage) {
            if (in_array($validLanguage, $acceptedLanguages)) {
                return $validLanguage;
            }
        }

        if (in_array('*', $acceptedLanguages)) {
            return array_shift($validLanguages);
        }

        return $default;
    }
}
