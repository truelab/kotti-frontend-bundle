<?php

namespace Truelab\KottiFrontendBundle\Pagerfanta\Adapter;
use Pagerfanta\Adapter\AdapterInterface;

/**
 * Class CallableAdapter
 * @package Truelab\KottiFrontendBundle\Pagerfanta\Adapter
 */
class CallableAdapter implements AdapterInterface
{
    private $count;
    private $find;

    public function __construct(callable $count, callable $find)
    {
        $this->count = $count;
        $this->find  = $find;
    }

    /**
     * Returns the number of results.
     *
     * @return integer The number of results.
     */
    public function getNbResults()
    {
        return call_user_func($this->count);
    }

    /**
     * Returns an slice of the results.
     *
     * @param integer $offset The offset.
     * @param integer $length The length.
     *
     * @return array|\Traversable The slice.
     */
    public function getSlice($offset, $length)
    {
        return call_user_func($this->find, $offset, $length);
    }
}
