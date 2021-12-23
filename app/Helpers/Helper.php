<?php

namespace App\Helpers;

use Symfony\Polyfill\Mbstring\Mbstring;

class Helper
{
    /**
     * @throws Exception
     */
    public static function generateNumber()
    {
        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil(10 / 2));
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil(10 / 2));
        } else {
            throw new \Exception("no cryptographically secure random function available");
        }
        return substr(bin2hex($bytes), 0, 10);
    }

    /**
     * @throws Exception
     */
    public static function generateOrderNumber()
    {
        return self::generateNumber();
    }

    public static function strToUpper($str)
    {
        return Mbstring::mb_strtoupper($str, 'UTF-8');
    }

    public static function replaceStr($needle, $haystack, $replaceWith = '')
    {
        return str_replace($needle, $replaceWith, $haystack);
    }

    public static function strToLower($str)
    {
        return mb_convert_case(str_replace('I', 'ı', trim($str)), MB_CASE_LOWER, 'UTF-8');
    }

    public static function isSubStr($haystack, $needle)
    {
        return $needle === "" || stripos($haystack, $needle) !== false;
    }
}
