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
            new \Twig_SimpleFunction('kotti_breadcrumbs', array($this, 'breadcrumbs'))
        );
    }

    public function breadcrumbs()
    {
        return $this->templateApi->breadcrumbs();
    }

    public function path($context)
    {
        return $this->templateApi->path($context);
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
