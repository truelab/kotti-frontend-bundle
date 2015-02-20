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
     * @return string
     */
    public function getPath($context);
}
