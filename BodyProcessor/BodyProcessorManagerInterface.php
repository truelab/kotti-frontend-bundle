<?php

namespace Truelab\KottiFrontendBundle\BodyProcessor;

/**
 * Class HtmlBodyProcessorManager
 * @package Truelab\KottiFrontendBundle\BodyProcessor
 */
interface BodyProcessorManagerInterface
{
    public function addProcessor($processor);

    public function process($input);
}
