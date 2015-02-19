<?php

namespace Truelab\KottiFrontendBundle\BodyProcessor\Html;

/**
 * Interface BodyProcessorInterface
 * @package Truelab\KottiFrontendBundle\BodyProcessor
 */
interface BodyProcessorInterface
{
    /**
     * @param $input
     *
     * @return string
     */
    public function process($input);
}
