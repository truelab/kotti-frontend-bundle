<?php

namespace Truelab\KottiFrontendBundle\BodyProcessor\Html;

use Sunra\PhpSimple\HtmlDomParser;
use Truelab\KottiFrontendBundle\BodyProcessor\AbstractBodyProcessorManager;

/**
 * Class BodyProcessorManager
 * @package Truelab\KottiFrontendBundle\BodyProcessor
 */
class BodyProcessorManager extends AbstractBodyProcessorManager
{
    public function process($input)
    {
        if(!$input) {
            return $input;
        }

        $html = HtmlDomParser::str_get_html($input);

        /**
         * @var BodyProcessorInterface $processor
         */
        foreach($this->getProcessors() as $processor)
        {
            $html = $processor->process($html);
        }

        return $html->__toString();
    }

}
