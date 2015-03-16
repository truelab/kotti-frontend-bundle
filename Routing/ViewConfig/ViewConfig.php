<?php

namespace Truelab\KottiFrontendBundle\Routing\ViewConfig;

/**
 * @Annotation
 *
 * Class ViewConfig
 * @package Truelab\KottiFrontendBundle\Routing\ViewConfig
 */
class ViewConfig
{
    private $options;

    private $controller;

    public function __construct($options)
    {
        $this->options = $options ? $options : [];
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getName()
    {
        return isset($this->options['name']) ? $this->options['name'] : null;
    }

    public function setController($controller)
    {
        $this->controller =  $controller;
    }

    public function getController()
    {
        return $this->controller;
    }
}
