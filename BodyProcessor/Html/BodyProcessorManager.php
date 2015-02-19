<?php

namespace Truelab\KottiFrontendBundle\BodyProcessor\Html;

use Truelab\KottiFrontendBundle\BodyProcessor\AbstractBodyProcessorManager;

/**
 * Class BodyProcessorManager
 * @package Truelab\KottiFrontendBundle\BodyProcessor
 */
class BodyProcessorManager extends AbstractBodyProcessorManager
{
    public function process($input)
    {
        $output = $input;

        /**
         * @var BodyProcessorInterface $processor
         */
        foreach($this->getProcessors() as $processor)
        {
            $output = $processor->process($output);
        }
        return $output;
    }

}
