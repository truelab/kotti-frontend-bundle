<?php

namespace Truelab\KottiFrontendBundle\Twig;
use Truelab\KottiFrontendBundle\Util\TemplateApi;

/**
 * Class KottiExtension
 * @package Truelab\KottiFrontendBundle\Twig
 */
class KottiExtension extends \Twig_Extension
{
    private $templateApi;

    public function __construct(TemplateApi $templateApi)
    {
        $this->templateApi = $templateApi;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('kotti_path', array($this, 'path')),
            new \Twig_SimpleFunction('kotti_breadcrumbs', array($this, 'breadcrumbs')),
            new \Twig_SimpleFunction('kotti_active_link_class', array($this, 'activeLinkClass')),
            new \Twig_SimpleFunction('kotti_option', array($this, 'option'))
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
