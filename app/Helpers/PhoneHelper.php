<?php

namespace App\Helpers;

class PhoneHelper
{
    public static function getPhoneTreated(string $phone): string
    {           
        $phoneOnlyNumbers = preg_replace('/\D/', '', $phone);

        if (substr($phoneOnlyNumbers, 0, 2) === '55') {
            $phoneOnlyNumbers = substr($phone, 2);
        }

        if (strlen($phoneOnlyNumbers) === 11) {
            $pattern = "/(\d{2})(\d{5})(\d{4})/";
            $format = "($1) $2-$3";
        } elseif (strlen($phoneOnlyNumbers) === 10) {
            $pattern = "/(\d{2})(\d{4})(\d{4})/";
            $format = "($1) $2-$3";
        } else {
            return $phoneOnlyNumbers;
        }

        return '+55 ' . preg_replace($pattern, $format, $phoneOnlyNumbers);
    }
}
