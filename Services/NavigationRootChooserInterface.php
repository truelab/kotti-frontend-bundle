<?php

namespace Truelab\KottiFrontendBundle\Services;

/**
 * Interface NavigationRootChooserInterface
 * @package Truelab\KottiFrontendBundle\Services
 */
interface NavigationRootChooserInterface
{
    public function choose($node);
}
