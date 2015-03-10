<?php

namespace Truelab\KottiFrontendBundle\Util;

/**
 * Interface PathHandlerInterface
 * @package Truelab\KottiFrontendBundle\Util
 */
interface PathHandlerInterface
{
    /**
     * @param $context
     *
     * @return bool
     */
    public function support($context);

    /**
     * @param $context
     *
     * @param TemplateApi $templateApi
     *
     * @return string
     */
    public function getPath($context, TemplateApi $templateApi);
}
