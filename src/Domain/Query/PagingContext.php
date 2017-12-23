<?php

namespace App\Domain\Query;

final class PagingContext
{
    /**
     * @var int
     */
    private $page;

    /**
     * @var int
     */
    private $pageSize;

    private function __construct(int $page, $pageSize)
    {
        $this->page = $page;
        $this->pageSize = $pageSize;
    }

    public static function create(int $page = 0, int $pageSize = 100): PagingContext
    {
        return new self($page, $pageSize);
    }

    public function page(): int
    {
        return $this->page;
    }

    public function pageSize(): int
    {
        return $this->pageSize;
    }

    public function offset(): int
    {
        return $this->page * $this->pageSize;
    }
}
