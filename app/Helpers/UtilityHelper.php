<?php

namespace App\Helpers;
use DateTime;
use App\Models\Airport;

class UtilityHelper {
    public static function minuteToHourMinute($time, $format = '%02d:%02d') {
        if ($time < 1) { return 0; }
        $hours = floor($time / 60);
        $minutes = ($time % 60);
        return sprintf($format, $hours, $minutes);
    }

    public static function addHoursToTimestamp($timestamp, $hour) {
        $time = date('Y-m-d H:m:s', $timestamp);
        $cTime = strtotime("+$hour hours", strtotime($time));
        return date('Y-m-d H:m:s', $cTime);
    }

    public static function dobToAge($dob) {
        $diff = date_diff(date_create($dob), date_create(date("Y-m-d")));
        return str_pad($diff->format('%y'), 2, '0', STR_PAD_LEFT);
    }

    public static function dobToTotalMonths($dob) {
        $start = new DateTime('2022-01-01');
        $end = new DateTime(date('Y-m-d')); // TODO, lest say a infant will become a child during onboard, then what will happen?
        $diff = $start->diff($end);
        $yearsInMonths = $diff->format('%r%y') * 12;
        $months = $diff->format('%r%m');
        return str_pad($yearsInMonths + $months, 2, '0', STR_PAD_LEFT);
    }

    public static function addDayToDate($date, $num) {
        $date = new DateTime($date);
        $date->modify("+$num day");
        return $date->format('Y-m-d');
    }

    public static function randomString() {
        $bytes = random_bytes(10);
        return bin2hex($bytes);
    }

    public static function differenceInHours($startdate,$enddate) {
        $starttimestamp = strtotime($startdate);
        $endtimestamp = strtotime($enddate);
        $difference = abs($endtimestamp - $starttimestamp)/3600;
        return $difference;
    }

    public static function cityCodeToName($code) {
        $a = Airport::where('code', $code)->first();
        if (!$a) {
            return null;
        }

        return explode(',', $a->name)[0];
    }

}
