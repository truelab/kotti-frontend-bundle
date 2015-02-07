<?php

namespace Truelab\KottiFrontendBundle\Services;
use Truelab\KottiModelBundle\Model\NodeInterface;

/**
 * Class CurrentContext
 * @package Truelab\KottiFrontendBundle\Services
 */
class CurrentContext
{
    private $context;

    public function set(NodeInterface $context)
    {
        $this->context = $context;
    }

    public function get()
    {
        return $this->context;
    }
}
