<?php

namespace Truelab\KottiFrontendBundle\BodyProcessor;

/**
 * Class AbstractBodyProcessorManager
 * @package Truelab\KottiFrontendBundle\BodyProcessor
 */
abstract class AbstractBodyProcessorManager implements BodyProcessorManagerInterface
{
    protected $processors = [];

    public function addProcessor($processor)
    {
        $this->processors[] = $processor;
    }

    public function getProcessors()
    {
        return $this->processors;
    }
}
