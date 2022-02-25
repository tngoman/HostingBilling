<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 

function date_formats()
{
    return array(
        'm/d/Y' => array(
            'setting'    => 'm/d/Y',
            'datepicker' => 'mm/dd/yyyy'
        ),
        'm-d-Y' => array(
            'setting'    => 'm-d-Y',
            'datepicker' => 'mm-dd-yyyy'
        ),
        'm.d.Y' => array(
            'setting'    => 'm.d.Y',
            'datepicker' => 'mm.dd.yyyy'
        ),
        'Y/m/d' => array(
            'setting'    => 'Y/m/d',
            'datepicker' => 'yyyy/mm/dd'
        ),
        'Y-m-d' => array(
            'setting'    => 'Y-m-d',
            'datepicker' => 'yyyy-mm-dd'
        ),
        'Y.m.d' => array(
            'setting'    => 'Y.m.d',
            'datepicker' => 'yyyy.mm.dd'
        ),
        'd/m/Y' => array(
            'setting'    => 'd/m/Y',
            'datepicker' => 'dd/mm/yyyy'
        ),
        'd-m-Y' => array(
            'setting'    => 'd-m-Y',
            'datepicker' => 'dd-mm-yyyy'
        ),
        'd.m.Y' => array(
            'setting'    => 'd.m.Y',
            'datepicker' => 'dd.mm.yyyy'
        )
    );
}

function date_from_mysql($date, $ignore_post_check = FALSE)
{
    if ($date <> '0000-00-00')
    {
        if (!$_POST or $ignore_post_check)
        {
            $CI = & get_instance();

            $date = DateTime::createFromFormat('Y-m-d', $date);
            return $date->format($CI->mdl_settings->setting('date_format'));
        }
        return $date;
    }
    return '';
}

function date_from_timestamp($timestamp)
{
    $CI = & get_instance();
    
    $date = new DateTime();
    $date->setTimestamp($timestamp);
    return $date->format($CI->mdl_settings->setting('date_format'));
}

function date_to_mysql($date)
{
    $CI = & get_instance();

    $date = DateTime::createFromFormat($CI->mdl_settings->setting('date_format'), $date);
    return $date->format('Y-m-d');
}

function format_date($date){
    $CI = & get_instance();
    return strftime($CI->config->item('date_format'), strtotime($date));
}

function date_format_setting()
{
    $CI = & get_instance();

    $date_format = $CI->mdl_settings->setting('date_format');

    $date_formats = date_formats();

    return $date_formats[$date_format]['setting'];
}

function date_format_datepicker()
{
    $CI = & get_instance();

    $date_format = $CI->mdl_settings->setting('date_format');

    $date_formats = date_formats();

    return $date_formats[$date_format]['datepicker'];
}

/**
 * Adds interval to user formatted date and returns user formatted date
 * To be used when date is being output back to user
 * @param $date - user formatted date
 * @param $increment - interval (1D, 2M, 1Y, etc)
 * @return user formatted date
 */
function increment_user_date($date, $increment)
{
    $CI = & get_instance();

    $mysql_date = date_to_mysql($date);

    $new_date = new DateTime($mysql_date);
    $new_date->add(new DateInterval('P' . $increment));

    return $new_date->format($CI->mdl_settings->setting('date_format'));
}

function timelog($str_time){
    sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
    return isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;
}

function valid_date($string){
    return (bool)strtotime($string);
}

/**
 * Adds interval to yyyy-mm-dd date and returns in same format
 * @param $date
 * @param $increment
 * @return date
 */
function increment_date($date, $increment)
{
    $new_date = new DateTime($date);
    $new_date->add(new DateInterval('P' . $increment));
    return $new_date->format('Y-m-d');
}

/**
     * Returns time difference between two timestamps, in human readable format.
     *
     * @param int $time1 A timestamp.
     * @param int $time2 A timestamp, defaults to the current time.
     * @param string $output Formatting string specifying which parts of the date to return in the array.
     * @return string|array
     */
function timespan($time1, $time2 = null, $output = 'years,months,weeks,days,hours,minutes,seconds')
    {
        // Array with the output formats
        $output = preg_split('/[^a-z]+/', strtolower((string) $output));
        // Invalid output
        if (empty($output)) {
            return false;
        }
        // Make the output values into keys
        extract(array_flip($output), EXTR_SKIP);
        // Default values
        $time1  = max(0, (int) $time1);
        $time2  = empty($time2) ? time() : max(0, (int) $time2);
        // Calculate timespan (seconds)
        $timespan = abs($time1 - $time2);
        // All values found using Google Calculator.
        // Years and months do not match the formula exactly, due to leap years.
        // Years ago, 60 * 60 * 24 * 365
        if (isset($years) ) {
            $timespan -= 31556926 * ($years = (int) floor($timespan / 31556926));
        }
        // Months ago, 60 * 60 * 24 * 30
        if (isset($months)) {
            $timespan -= 2629744 * ($months = (int) floor($timespan / 2629743.83));
        }
        // Weeks ago, 60 * 60 * 24 * 7
        if (isset($weeks)) {
            $timespan -= 604800 * ($weeks = (int) floor($timespan / 604800));
        }
        // Days ago, 60 * 60 * 24
        if (isset($days)) {
            $timespan -= 86400 * ($days = (int) floor($timespan / 86400));
        }
        // Hours ago, 60 * 60
        if (isset($hours)) {
            $timespan -= 3600 * ($hours = (int) floor($timespan / 3600));
        }
        // Minutes ago, 60
        if (isset($minutes)) {
            $timespan -= 60 * ($minutes = (int) floor($timespan / 60));
        }
        // Seconds ago, 1
        if (isset($seconds)) {
            $seconds = $timespan;
        }
        // Remove the variables that cannot be accessed
        unset($timespan, $time1, $time2);
        // Deny access to these variables
        $deny = array('deny', 'key', 'difference', 'output');
        // Return the difference
        $difference = array();
        foreach ($output as $key) {
            if (isset($$key) AND !in_array($key, $deny)) {
                // Add requested key to the output
                $difference[$key] = $$key;
            }
        }
        // Invalid output formats string
        if (empty($difference)) {
            return false;
        }
        // If only one output format was asked, don't put it in an array
        if (count($difference) === 1) {
            return current($difference);
        }
        // Return array
        return $difference;
    }
    /**
     * Expands upon the functionality provided by Date::timespan(), such that when provided
     * with two timestamps (or one and using time() as the second), it will intelligently
     * predict the human readable date format to use, based on the length of the timespan.
     * Consequently the time string returned is more of an approximation - it will in some
     * cases be less than the length of the actual period, but never more.
     *
     * timespan < 1 minute: 'X seconds'
     * timespan < 1 hour: 'X minutes'
     * timespan < 1 day: 'X hours'
     * timespan < 1 week: 'X days'
     * timespan < 1 month: 'X weeks'
     * timespan < 1 year: 'X months'
     *
     * It will also unpluralise if the unit is 1, so 1 seconds becomes 1 second.
     *
     * @param int $time1 A timestamp.
     * @param int|false $time2 A timestamp, defaults to the current time (passing null). If set to false, the
     * first parameter will be treated as the period on its own.
     * @return string Human readable format
     */

function humanFormat($time1, $time2 = null)
    {
        // Default values
        $time1  = max(0, (int) $time1);
        if($time2 !== false) {
            $time2  = empty($time2) ? time() : max(0, (int) $time2);
            // Calculate timespan (seconds)
            $period = abs($time1 - $time2);
        } else {
            $period = $time1;
        }
        $format = 'seconds';
        if ($period > 31556926) {
            // More than one year
            $format = 'years';
        }
        elseif ($period > 2629744) {
            // More than one month
            $format = 'months';
        }
        elseif ($period > 604800) {
            // More than one week
            $format = 'weeks';
        }
        elseif ($period > 86400) {
            // More than one day
            $format = 'days';
        }
        elseif ($period > 3600) {
            // More than one hour
            $format = 'hours';
        }
        elseif ($period > 60) {
            // More than one minute
            $format = 'minutes';
        }
        if($time2 !== false) {
            // Get timespan output
            $timespan = timespan($time1, $time2, $format);
        } else {
            $timespan = $time1;
        }
        // Remove the s
        if($timespan == 1) {
            $format = substr($format, 0, -1);
        }
        return $timespan . ' ' . $format;
    }

?>