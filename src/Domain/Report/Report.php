<?php

namespace App\Domain\Report;

use Functional as F;

class Report
{
    public static function aggregate(array $dataSet)
    {
        $config = array_merge([
            'groups' => [ 'benchmark-class' ],
        ], []);

        $report = new self();
        $dataSet = $report->sort($dataSet);
        $tables = $report->groupBy($dataSet, $config['groups']);

        return $tables;
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
                $groupHash[] = $key. ': ' .$row[$key];
            }

            return implode(', ', $groupHash);
        });
    }
}
