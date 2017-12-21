<?php

namespace App\Domain\Report;

use ArrayAccess;
use IteratorAggregate;
use ArrayIterator;
use Iterator;

final class DataSet implements ArrayAccess, Iterator
{
    /**
     * @var array
     */
    private $array;

    private function __construct(array $array)
    {
        $this->array = $array;
    }

    public static function fromArray(array $array)
    {
        return new self($array);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->array[$offset]);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($offset)
    {
        $value = $this->array[$offset];

        if (is_array($value)) {
            return new self($value);
        }

        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value)
    {
        throw new Exception('Dataset is read-only');
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($offset)
    {
        throw new Exception('Dataset is read-only');
    }

    /**
     * {@inheritDoc}
     */
    public function current()
    {
        return current($this->array);
    }

    /**
     * {@inheritDoc}
     */
    public function next()
    {
        return next($this->array);
    }

    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return key($this->array);
    }

    /**
     * {@inheritDoc}
     */
    public function valid()
    {
        return key($this->array) !== null;
    }

    /**
     * {@inheritDoc}
     */
    public function rewind()
    {
        return reset($this->array);
    }

    public function toArray(): array
    {
        return $this->array;
    }
}
