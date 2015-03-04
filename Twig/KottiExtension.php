<?php

namespace Truelab\KottiFrontendBundle\Twig;
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

    public function __construct(TemplateApi $templateApi, BodyProcessorManagerInterface $bodyProcessor)
    {
        $this->templateApi = $templateApi;
        $this->bodyProcessor = $bodyProcessor;
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
            new \Twig_SimpleFunction('kotti_type_class', function ($input) {
                return $this->templateApi->getTypeClass($input);
            })
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

    public function path($context)
    {
        return $this->templateApi->path($context);
    }

    public function imagePath($context, $options = [])
    {
        return $this->templateApi->imagePath($context, $options);
    }

    public function activeLinkClass($link)
    {
        return $this->templateApi->activeLinkClass($link);
    }

    public function option($key, $default = null)
    {
        return $this->templateApi->option($key, $default);
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
