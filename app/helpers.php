<?php

if (!function_exists('generate_code_from_name')) {
    /**
     * Generate code from a given name.
     *
     * @param string $name
     * @return string
     */
    function generate_code_from_name($name)
    {
        $code = strtolower($name);
        $code = str_replace([' ', 'ç', 'ğ', 'ı', 'ö', 'ş', 'ü', 'Ç', 'Ğ', 'İ', 'Ö', 'Ş', 'Ü'], ['-', 'c', 'g', 'i', 'o', 's', 'u', 'c', 'g', 'i', 'o', 's', 'u'], $code);
        $code = preg_replace('/[^A-Za-z0-9\-]/', '', $code);
        $code = preg_replace('/-+/', '-', $code);
        return $code;
    }
}
