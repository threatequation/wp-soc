<?php

namespace SOCLITE\Detector\Converter;

/**
 * Centrifuge is a spesical function that count encoded cherector
 * give a retio of input.
 * Using this function we can find new attack string 
 */
class Centrifuge
{

 /**
     * This method is the centrifuge prototype
     *
     * @param string  $value   the value to convert
     * @param Monitor $monitor the monitor object
     *
     * @static
     * @return string
     */
    public function runCentrifuge($value, \SOCLITE\Detector\Validator $monitor = null)
    {
        $threshold = 3.49;
        if (strlen($value) > 25) {
            //strip padding
            $tmp_value = preg_replace('/\s{4}|==$/m', null, $value);
            $tmp_value = preg_replace(
                '/\s{4}|[\p{L}\d\+\-=,.%()]{8,}/m',
                'aaa',
                $tmp_value
            );

            // Check for the attack char ratio
            $tmp_value = preg_replace('/([*.!?+-])\1{1,}/m', '$1', $tmp_value);
            $tmp_value = preg_replace('/"[\p{L}\d\s]+"/m', null, $tmp_value);

            $stripped_length = strlen(
                preg_replace(
                    '/[\d\s\p{L}\.:,%&\/><\-)!|]+/m',
                    null,
                    $tmp_value
                )
            );
            $overall_length = strlen(
                preg_replace(
                    '/([\d\s\p{L}:,\.]{3,})+/m',
                    'aaa',
                    preg_replace('/\s{2,}/m', null, $tmp_value)
                )
            );

            if ($stripped_length != 0 && $overall_length/$stripped_length <= $threshold) {
                $monitor->centrifuge['ratio']     = $overall_length / $stripped_length;
                $monitor->centrifuge['threshold'] =$threshold;

                $value .= "\n$[!!!]";
            }
        }

        if (strlen($value) > 40) {
            // Replace all non-special chars
            $converted =  preg_replace('/[\w\s\p{L},.:!]/', null, $value);

            // Split string into an array, unify and sort
            $array = str_split($converted);
            $array = array_unique($array);
            asort($array);

            // Normalize certain tokens
            $schemes = array(
                '~' => '+',
                '^' => '+',
                '|' => '+',
                '*' => '+',
                '%' => '+',
                '&' => '+',
                '/' => '+'
            );

            $converted = implode($array);

            $_keys = array_keys($schemes);
            $_values = array_values($schemes);

            $converted = str_replace($_keys, $_values, $converted);

            $converted = preg_replace('/[+-]\s*\d+/', '+', $converted);
            $converted = preg_replace('/[()[\]{}]/', '(', $converted);
            $converted = preg_replace('/[!?:=]/', ':', $converted);
            $converted = preg_replace('/[^:(+]/', null, stripslashes($converted));

            // Sort again and implode
            $array = str_split($converted);
            asort($array);
            $converted = implode($array);

            if (preg_match('/(?:\({2,}\+{2,}:{2,})|(?:\({2,}\+{2,}:+)|(?:\({3,}\++:{2,})/', $converted)) {
                $monitor->centrifuge['converted'] = $converted;

                return $value . "\n" . $converted;
            }
        }

        return $value;
    }
}
