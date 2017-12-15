<?php

namespace App\Domain\Report;

use MathPHP\Statistics\Average;

class IterationReport
{
    public static function iterations(array $dataSet)
    {
        return $dataSet;
    }


    public static function chart(array $dataSet)
    {
        $data = [];
        $labels = array_map(function (array $data) {
            return $data['iteration'];
        }, $dataSet);

        $series = array_map(function (array $data) {
            return $data['time-net'];
        }, $dataSet);

        return [
            'labels' => $labels,
            'series' => $series,
            'mean' => Average::mean($series),
        ];
    }
}
