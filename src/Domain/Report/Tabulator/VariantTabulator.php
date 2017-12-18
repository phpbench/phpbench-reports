<?php

namespace App\Domain\Report\Tabulator;

use Functional as F;

class VariantTabulator
{
    public function aggregate(array $dataSet)
    {
        $config = array_merge([
            'groups' => [ 'benchmark-class' ],
        ], []);

        $report = new self();
        $dataSet = $report->sort($dataSet);
        $tables = $report->groupBy($dataSet, $config['groups']);
        $tables = F\map($tables, (function ($table) {
            return $this->sort($table);
        })->bindTo($report));

        return $tables;
    }

    public static function chart(array $dataSet)
    {
        $report = new self();

        $labels = array_map(function (array $data) {
            return $data['subject-name'];
        }, $dataSet);

        $dataMode = array_map(function (array $data) {
            return $data['stats-mode'];
        }, $dataSet);

        return [
            'labels' => $labels,
            'series' => [
                'mode' => $dataMode,
            ],
        ];
    }

    private function sort(array $dataSet): array
    {
        $dataSet = F\sort($dataSet, function ($a, $b) {
            return $a['subject-name'] > $b['subject-name'];
        });
        return $dataSet;
    }

    private function groupBy(array $dataSet, $groupBy): array
    {
        if (empty($groupBy)) {
            return [$dataSet];
        }

        return F\group($dataSet, function ($row) use ($groupBy) {
            $groupHash = [];
            foreach ($groupBy as $key) {
                $groupHash[] = $row[$key];
            }

            return implode(', ', $groupHash);
        });
    }
}
