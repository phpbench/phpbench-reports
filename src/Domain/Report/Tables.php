<?php

namespace App\Domain\Report;

final class Tables implements \IteratorAggregate
{
    private $tables = [];

    private function __construct(array $tables, AggregateConfig $config)
    {
        foreach ($tables as $item) {
            $this->add($item);
        }
    }

    public static function fromDataSet(array $dataSet): Tables
    {
         return new self($dataSet);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->tables);
    }

    private function add(array $table)
    {
        $this->tables[] = $table;
    }

    public function toArray(): array
    {
        return $this->tables;
    }
}
