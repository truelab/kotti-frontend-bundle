<?php

namespace Truelab\KottiFrontendBundle\Routing\ViewConfig;

use Doctrine\Common\Annotations\Reader;
use ReflectionMethod;
use Truelab\KottiModelBundle\Model\ContentInterface;

/**
 * Class ViewConfigManager
 * @package Truelab\KottiFrontendBundle\Routing\ViewConfig
 */
class ViewConfigManager
{
    /**
     * @var Reader
     */
    private $annotationReader;

    /**
     * @var array
     */
    private $controllers;

    /**
     * @var string
     */
    private $annotationClass = 'Truelab\\KottiFrontendBundle\\Routing\\ViewConfig\\ViewConfig';

    /**
     * @var ViewConfig[]
     */
    private $viewConfigs = [];


    public function __construct(Reader $annotationReader, array $viewConfigControllers = [])
    {
        $this->annotationReader = $annotationReader;
        $this->controllers = $viewConfigControllers;


        $this->read();
    }

    private function read()
    {
        foreach($this->controllers as $controller) {
            $reflectionClass = new \ReflectionClass($controller);


            foreach($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {


                if(preg_match('/^.*Action$/', $method->getName())) {
                    $annotation = $this->annotationReader->getMethodAnnotation($method, $this->annotationClass);
                    if (null !== $annotation) {

                        $annotation->setController($reflectionClass->getName() . '::' . $method->getName());

                        $this->viewConfigs[] = $annotation;
                    }
                }
            }
        }
    }

    /**
     * @param ContentInterface $content
     *
     * @return ViewConfig|null
     */
    public function match(ContentInterface $content)
    {
        $name = $content->getDefaultView();

        if(null !== $name) {
            foreach($this->viewConfigs as $viewConfig) {
                if($viewConfig->getName() === $name) {
                    return $viewConfig;
                }
            }
        }

        return null;
    }
}
