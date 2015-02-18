<?php

namespace Truelab\KottiFrontendBundle\Util;

use Truelab\KottiModelBundle\Model\NodeInterface;
use Truelab\KottiFrontendBundle\Services\CurrentContext;

/**
 * Class TemplateApi
 * @package Truelab\KottiFrontendBundle\Util
 */
class TemplateApi
{
    protected $config;
    protected $lineage;
    protected $options;

    public function __construct($config, CurrentContext $currentContext, $options = [])
    {
        $this->config = $config;
        $this->currentContext = $currentContext;
        $this->options = $options;
    }

    public function path($context)
    {
        if (is_string($context)) {
            return $this->frontendDomain($context);
        }

        if ($context instanceof NodeInterface) {
            return $this->frontendDomain($context->getPath());
        }

        throw new \RuntimeException(sprintf('I can\'t generate a url for "%s"', get_class($context)));
    }

    public function getConfig()
    {
        return $this->config;
    }

    protected function getContext()
    {
        return $this->currentContext->get();
    }

    public function navigationRoot()
    {
        return $this->currentContext->navigationRoot();
    }

    public function root()
    {
        return $this->currentContext->root();
    }

    /**
     * @return NodeInterface[]
     */
    public function lineage()
    {
        return $this->currentContext->lineage();
    }

    public function breadcrumbs()
    {

        $breadcrumbs = array_reverse($this->lineage());
        $context = $this->currentContext->get();

        if($context->equals($this->navigationRoot())) {
            return [];
        }

        $index = null;
        foreach($breadcrumbs as $i => $breadcrumb)
        {
            if($breadcrumb->equals($this->navigationRoot())) {
                $index = $i;
                break;
            }
        }

        array_push($breadcrumbs, $context);

        if($index !== null) {
            return array_slice($breadcrumbs, $index);
        }



        return $breadcrumbs;
    }

    public function isActiveLink(NodeInterface $link)
    {
        $context = $this->getContext();
        return $this->startsWith($context->getPath(), $link->getPath());
    }

    public function activeLinkClass(NodeInterface $link)
    {
        return $this->isActiveLink($link) ? 'active' : '';
    }

    /**
     * @param $key
     * @param null $default
     *
     * @return mixed|null
     */
    public function option($key, $default = null)
    {
        if(isset($this->options[$key])) {
            return $this->options[$key];
        }
        return $default;
    }

    protected function frontendDomain($path)
    {
        return rtrim($this->config['domain'], '/') . $path;
    }

    protected static function startsWith($haystack, $needle, $case = false)
    {
        if ($case) {
            return strpos($haystack, $needle, 0) === 0;
        }
        return stripos($haystack, $needle, 0) === 0;
    }
}
