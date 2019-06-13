<?php

namespace FilippoToso\LaravelHelpers\UI;

class Gravatar
{
    public function get($email, $size = null, $fallback = null)
    {
        $params = [];

        if ($size) {
            $size = max(1, min(1024, $size));
            $params['s'] = $size;
        }

        if ($fallback) {
            $params['d'] = $fallback;
        }

        return sprintf('https://www.gravatar.com/avatar/%s?%s', md5(trim($email)), http_build_query($params));
    }
}
