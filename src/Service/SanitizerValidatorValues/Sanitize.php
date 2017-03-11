<?php

namespace ApiMaster\Service\SanitizerValidatorValues;

class Sanitize
{
    public function stringSpecialCaracteres($input = null) {
        return $stringSpecial = filter_var($input, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    public function string($input = null)
    {
        $string = filter_var($input, FILTER_SANITIZE_STRING);
        return $sanitizeString = filter_var($this->stringSpecialCaracteres($string));
    }

    public function email($input = null) {
        return $email = filter_var($input, FILTER_SANITIZE_EMAIL);
    }

    public function url($input = null) {
        return $url = filter_var($input, FILTER_SANITIZE_URL);
    }
}
