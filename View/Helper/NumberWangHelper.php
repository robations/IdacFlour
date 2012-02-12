<?php

App::import('Helper', 'Number');
App::import('Vendor', 'IdeasNumberFormatter', array('file' => 'ideas' . DS . 'class.ideas_number_formatter.php'));

class NumberWangHelper extends NumberHelper
{
    /**
     * Formats a number into a currency format, with no decimal places if the 
     * given number is whole
     *
     * @param float $number
     * @param string $currency Shortcut to default option. Valid values are 
     *    'USD', 'EUR', 'GBP', otherwise set at least 'before' and 'after' 
     *    options
     * @param array $options
     * @return string Number formatted as a currency
     */
    public function shortCurrency($number, $currency = 'USD', $options = array())
    {
        if (false == is_numeric($number))
        {
            return $number;
        }
        $r = (string) round($number, 2);
        $w = (string) round($number, 0);
        if ($r == $w)
        {
            $options['places'] = 0;
        }
        return $this->currency($number, $currency, $options);
    }

    /**
     * Formats a number into a currency format
     *
     * @param float|string $number
     * @param string $currency Shortcut to default option. Valid values are 
     *    'USD', 'EUR', 'GBP', otherwise set at least 'before' and 'after' 
     *    options
     * @param array $options
     * @return string Number formatted as a currency, or if $number is not numeric
     *      returns $number
     */
    public function currency($number, $currency = 'USD', $options = array())
    {
        if ($number == '')
        {
            return '-';
        }
        $nf = new IdeasNumberFormatter('en_GB', 0);
        $parsed = $nf->parse($number, IdeasNumberFormatter::TYPE_DEFAULT);
        if (false == is_numeric($parsed))
        {
            return $number;
        }
        $currencySyms = array(
            'USD' => '$',
            'GBP' => '£',
            'EUR' => '€',
        );
        $options = array_merge(array(
            'before' => isset($currencySyms[$currency]) ? $currencySyms[$currency] 
                : $currency,
            'after' => '',
            'places' => 2
        ), $options);
        return $this->format($parsed, $options);
    }

    /**
     * Parse as integer
     *
     * @param string $value
     * @return int
     */
    public function parseInt($value)
    {
        $nf = new IdeasNumberFormatter('en_GB', 0);
        $parsed = $nf->parse($value, IdeasNumberFormatter::TYPE_INT64);
        return $parsed;
    }

    /**
     * Parse as double
     *
     * @param string $value
     * @return double
     */
    public function parseCurrency($value)
    {
        $nf = new IdeasNumberFormatter('en_GB', 0);
        $parsed = $nf->parse($value, IdeasNumberFormatter::TYPE_CURRENCY);
        return $parsed;
    }
}
