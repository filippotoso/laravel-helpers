<?php

namespace FilippoToso\LaravelHelpers\Utils;

class ItalianNames
{

    public $fullName = null;
    public $firstName = null;
    public $lastName = null;

    public function __construct($fullName)
    {
        $this->fullName = $fullName;

        $prefix = "#^D'?|Conte|Da|Dai|Dal|Dalla|Dalle|De|Degli|Dei|Del|Della|Delle|Delli|Dello|Di|Don|La|Le|Li|Lo|Re|San|Tre$#si";

        $this->fullName = preg_replace('#\sAdmin$#si', '', $this->fullName);

        $names = preg_split('#\s+#si', $this->fullName);
        $count = count($names);
        if ($count == 1) {
            $this->firstName = $names[0];
            $this->lastName = '';
        } elseif ($count == 2) {
            $this->firstName = $names[0];
            $this->lastName = $names[1];
        } elseif ($count > 2) {
            if (preg_match($prefix, $names[$count - 2])) {
                $this->firstName = implode(' ', array_slice($names, 0, -2));
                $this->lastName = implode(' ', array_slice($names, -2));
            } else {
                $this->firstName = implode(' ', array_slice($names, 0, -1));
                $this->lastName = implode(' ', array_slice($names, -1));
            }
        }
    }

    public static function parse($fullName)
    {
        return new static($fullName);
    }
}
