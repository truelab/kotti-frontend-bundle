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

    public function __construct($config, CurrentContext $currentContext)
    {
        $this->config = $config;
        $this->currentContext = $currentContext;
    }

    public function path(NodeInterface $context)
    {
        return rtrim($this->config['domain'], '/') . $context->getPath();
    }

    public function getConfig()
    {
        return $this->config;
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

        if($this->currentContext->get()->equals($this->navigationRoot())) {
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

        if($index !== null) {
            return array_slice($breadcrumbs, $index);
        }


        return $breadcrumbs;
    }
}
