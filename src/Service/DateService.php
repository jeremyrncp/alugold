<?php


namespace App\Service;


class DateService
{
    public static $month = [
        '01' => 'Janvier',
        '02' => 'Février',
        '03' => 'Mars',
        '04' => 'Avril',
        '05' => 'Mai',
        '06' => 'Juin',
        '07' => 'Juillet',
        '08' => 'Août',
        '09' => 'Septembre',
        '10' => 'Octobre',
        '11' => 'Novembre',
        '12' => 'Décembre'
    ];

    public static function monthYeartoFrench(string $monthYear)
    {
        return static::$month[substr($monthYear, 0,2)] . " " . substr($monthYear, -4, 4);
    }
}