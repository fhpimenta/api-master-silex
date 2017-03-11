<?php

namespace ApiMaster\Service\SanitizerValidatorValues;

class Validate
{
    public function string($input = null)
    {
        if (is_string($input) == false) return $input;
        $removingSymbolsNumbers = preg_replace("/[^a-zA-Z\s]/", "", $input); //remove caracteres especiais e numeros;
        return $string = $removingSymbolsNumbers;
    }

    public function phone($input = null) {
        return !empty($input) && preg_match('/^[+]?([\d]{0,3})?[\(\.\-\s]?(([\d]{1,3})[\)\.\-\s]*)?(([\d]{3,5})[\.\-\s]?([\d]{4})|([\d]{2}[\.\-\s]?){4})$/', $input);
    }

    public function email($input = null) {
        return is_string($input) && filter_var($input, FILTER_VALIDATE_EMAIL);
    }

    public function url($input = null) {
        return $url = filter_var($input, FILTER_VALIDATE_URL);
    }
}
