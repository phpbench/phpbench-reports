<?php

namespace App\Domain\Query;

class PagerContext
{
    /**
     * @var int
     */
    private $pageSize;

    /**
     * @var int
     */
    private $page;

    private function __construct(int $pageSize, int $page)
    {
        $this->pageSize = $pageSize;
        $this->page = $page;
    }

    public function create(int $pageSize, int $page)
    {
        return new self($pageSize, $page);
    }

    public function pageSize(): int
    {
        return $this->pageSize;
    }

    public function page(): int
    {
        return $this->page;
    }

    public function from()
    {
        return $this->page * $this->pageSize;
    }

    public function isLastPage(int $nbHits)
    {
        return $nbHits < (($this->page + 1) * $this->pageSize);
    }
}
