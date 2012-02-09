<?php  
/** 
 * Date Helper 
 * 
 */  

App::import('Helper', 'Time');
App::import('Helper', 'Html');

class DateHelper extends TimeHelper
{ 
    private static function _getDiff($from = array() , $to = array() )
    { 
        $dateDiff =     mktime( $to['hour']    , $to['minutes']   , $to['seconds'] , 
                        $to['month']   , $to['day']       , $to['year'] ) 
                        - 
                        mktime( $from['hour']  , $from['minutes'] , $from['seconds'] , 
                        $from['month'] , $from['day']     , $from['year'] ); 
        return abs($dateDiff); 
    } 
     
    private static function _isValidDate( $sDate = "01/01/1980 00:00:00" )
    { 
        $dateString = split( " "    , $sDate); 
        $dateParts  = split( "[/-]" , $dateString[0]); 
        $dateParts2 = isset($dateString[1]) ? split( "[:]", $dateString[1]) : array('00','00','00'); 
        if( !checkdate($dateParts[1], $dateParts[2], $dateParts[0]) ) 
        {  return false; } 
        return array 
               ( 
                 'month'   => $dateParts[1] , 
                 'day'     => $dateParts[2] , 
                 'year'    => $dateParts[0] , 
                 'hour'    => $dateParts2[0] , 
                 'minutes' => $dateParts2[1] , 
                 'seconds' => $dateParts2[2] 
               ); 
    } 
     
    /**
     * Returns a fuzzy time difference as 'nearly x units' or 'over x units'
     *
     * @param string $dateFrom From datetime in 'YYYY-MM-DD HH:MM:SS' format
     * @param string $dateTo To datetime in 'YYYY-MM-DD HH:MM:SS' format
     * @return string
     * @access public
     * @static
     * @note  first parameter show be smaller than second parameter. 
     *        dates should be in this format 'yyyy-mm-dd 00:00:00' (time optional) 
     */
    public static function getDiff($dateFrom, $dateTo = null)
    {
        $dateTo = ($dateTo === null) ? date('Y-m-d H:i:s') : $dateTo;
        $from   = self::_isValidDate($dateFrom);
        $to     = self::_isValidDate($dateTo);
        if ($from && $to)
        {
            $dateDiff = self::_getDiff($from, $to); 
            $dd['years'] = $dateDiff / YEAR; 
            $dd['months'] = $dateDiff / MONTH;
            $dd['weeks'] = $dateDiff / WEEK;
            $dd['days'] = $dateDiff / DAY;
            $dd['hours'] = $dateDiff / HOUR; 
            $dd['minutes'] = $dateDiff / MINUTE; 
            $dd['seconds'] =  $dateDiff;
            foreach ($dd as $period => $amt)
            {
                $whole = floor($amt);
                $fract = $amt - $whole;
                if ($whole >= 1 || $fract >= 0.94)
                {
                    if ($fract >= 0.94)
                    {
                        return (__('almost') . " " . ($whole + 1) . " " 
                            . __n(rtrim($period, "s"), $period, $whole + 1)); 
                    }
                    return (__("over ") . $whole . " " 
                        . __n(rtrim($period, "s"), $period, $whole));
                }
            } 
        } 
        return ""; 
    }
    
    /**
     * Returns a date string with minimum length, for example:
     *
     * Today: today
     * This year: Mon 20 Jun
     * Previous: 20/06/2008
     *
     * @param string|int $date Date as string or UNIX timestamp
     * @return string
     */
    public function niceDate($dateString, $userOffset = null)
    {
        $date = $dateString ? $this->fromString($dateString, $userOffset) : time();
        
        if ($this->isToday($date))
        {
            return __('Today');
        }
        if ($this->wasYesterday($date))
        {
            return __('Yesterday');
        }
        if ($this->isThisYear($date))
        {
            return date(__('D j M'), $date);
        }
        return date(__('d/m/Y'), $date);
    }
    
    /**
     * Returns a date string as DateHelper::niceDate but wrapped in a span with
     * full datetime as title. 
     *
     * @param string|int $date Date as string or UNIX timestamp
     * @return string HTML as <span class="time" title="2010-10-09T09:07:03+00:00">Sat 9 Oct</span>
     */
    public function niceDateWrapper($dateString, $userOffset = null)
    {
        $date = $dateString ? $this->fromString($dateString, $userOffset) : time();
        $htmlHelper = new HtmlHelper();
        return sprintf('<span class="time" title="%2$s">%1$s</span>',
            $this->niceDate($dateString, $userOffset), date('c', $date));
    }

    /**
     * Format duration
     *
     * @param $duration Duration in seconds
     * @return string
     */
    public function formatDuration($duration)
    {
        $days = 0;
        $hours = 0;
        $mins = 0;
        $secs = 0;
        $remainder = $duration;
        
        $secs = $remainder % 60;
        $remainder = (int) ($remainder / 60);
        
        $mins = $remainder % 60;
        $remainder = (int) ($remainder / 60);
        
        $hours = $remainder % 24;
        $remainder = (int) ($remainder / 24);
        
        $days = $remainder;
        
        $format = '%1$d days %2$dhr %3$2d:%4$02d';
        if ($days == 0)
        {
            $format = '%2$dhr %3$2d:%4$02d';
        }
        if ($hours == 0)
        {
            $format = '%3$2d:%4$02d';
        }
        return sprintf($format, $days, $hours, $mins, $secs);
    }
}
?>
