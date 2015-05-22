<?php

namespace Truelab\KottiFrontendBundle\Twig;
use Pagerfanta\PagerfantaInterface;
use Symfony\Component\CssSelector\Node\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Truelab\KottiFrontendBundle\BodyProcessor\BodyProcessorManagerInterface;
use Truelab\KottiFrontendBundle\Util\TemplateApi;
use Truelab\KottiModelBundle\Util\ModelUtil;

/**
 * Class KottiExtension
 * @package Truelab\KottiFrontendBundle\Twig
 */
class KottiExtension extends \Twig_Extension
{
    private $templateApi;

    private $bodyProcessor;

    private $container;

    public function __construct(TemplateApi $templateApi,
                                BodyProcessorManagerInterface $bodyProcessor,
                                ContainerInterface $container)
    {
        $this->templateApi   = $templateApi;
        $this->bodyProcessor = $bodyProcessor;
        $this->container     = $container;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('kotti_raw', function ($input) {
                return $this->bodyProcessor->process($input);
            },array(
                'is_safe' => array('html')
            )),
            new \Twig_SimpleFilter('kotti_type', function ($input, $pattern) {
                return ModelUtil::filterByType($input, $pattern);
            }),
            new \Twig_SimpleFilter('kotti_in_navigation', function ($input, $inNavigation = true) {
                return ModelUtil::filterInNavigation($input, $inNavigation);
            })
        );
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('kotti_path', array($this, 'path')),
            new \Twig_SimpleFunction('kotti_breadcrumbs', array($this, 'breadcrumbs')),
            new \Twig_SimpleFunction('kotti_active_link_class', array($this, 'activeLinkClass')),
            new \Twig_SimpleFunction('kotti_option', array($this, 'option')),
            new \Twig_SimpleFunction('kotti_image_path', array($this, 'imagePath')),
            new \Twig_SimpleFunction('kotti_file_path', array($this, 'filePath')),
            new \Twig_SimpleFunction('kotti_type_class', function ($input) {
                return $this->templateApi->getTypeClass($input);
            }),
            new \Twig_SimpleFunction('kotti_pagerfanta', array($this, 'renderPagerfanta'),  array('is_safe' => array('html')))
        );
    }

    public function breadcrumbs($options = [])
    {
        $options = array_merge([
            'exclude_root' => false
        ], $options);

        $breadcrumbs = $this->templateApi->breadcrumbs();

        if(count($breadcrumbs) > 1 && $options['exclude_root'] === true) {
            array_shift($breadcrumbs);
        }

        return $breadcrumbs;
    }

    public function path($context, $parameters = array())
    {
        return $this->templateApi->path($context, $parameters);
    }

    public function imagePath($context, $options = [])
    {
        return $this->templateApi->imagePath($context, $options);
    }

    public function filePath($context)
    {
        return $this->templateApi->filePath($context);
    }

    public function activeLinkClass($link)
    {
        return $this->templateApi->activeLinkClass($link);
    }

    public function option($key, $default = null)
    {
        return $this->templateApi->option($key, $default);
    }

    public function renderPagerfanta(PagerfantaInterface $pagerfanta, $viewName = null, array $options = array())
    {
        if (null === $viewName) {
            $viewName = $this->container->getParameter('white_october_pagerfanta.default_view');
        }


        if(isset($options['context'])) {
            $context = $options['context'];
        }else{
            $context = ''; // FIXME
        }

        $routeGenerator = $this->createRouteGenerator($options, $context);

        return $this->container->get('white_october_pagerfanta.view_factory')->get($viewName)->render($pagerfanta, $routeGenerator, $options);
    }

    protected function createRouteGenerator($options = [], $context)
    {
        $request = $this->container->get('request');
        $defaultRouteParams = $request->query->all();
        $pageParameter = isset($options['pageParameter']) ? $options['pageParameter'] : 'page';

        return function ($page) use ($context, $defaultRouteParams, $pageParameter)
        {
            return $this->path($context, array_merge($defaultRouteParams,[ $pageParameter => $page ]));
        };
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'truelab_kotti';
    }
}
