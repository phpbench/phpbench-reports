<?php

namespace App\Domain\Report\Tabulator;

use Functional as F;

class VariantTabulator
{
    public function aggregate(array $dataSet, array $config = [])
    {
        $config = array_merge([
            'groups' => [ 'benchmark-class' ],
        ], $config);

        $tables = $this->groupBy($dataSet, $config['groups']);

        return $tables;
    }

    public function chart(array $dataSet)
    {
        // remove data rows without a mode
        $dataSet = array_filter($dataSet, function ($data) {
            return isset($data['stats-mode']);
        });

        // reindex the keys
        $dataSet = array_values($dataSet);

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

    public function historicalChart(array $dataSet)
    {
        $dataSet = array_filter($dataSet, function ($data) {
            return isset($data['stats-mode']);
        });
        $dataSet = array_values($dataSet);

        $grouped = $this->groupBy($dataSet, [ 'subject-name', 'variant-index' ]);

        $chart = [];
        $labels = [];
        foreach ($grouped as $variantName => $data) {
            if (empty($labels)) {
                $labels = array_values(array_map(function (array $data) {
                    return $data['suite-date'];
                }, $data));
            }

            $dataMode = array_map(function (array $data) {
                return $data['stats-mode'];
            }, $data);

            $series = [
                'label' => $variantName,
                'mode' => array_values($dataMode),
            ];
            $chart[] = $series;
        }

        return [
            'dataSets' => $chart,
            'labels' => $labels
        ];
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
