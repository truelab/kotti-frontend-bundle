<?php

namespace Truelab\KottiFrontendBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Truelab\KottiModelBundle\Model\NodeInterface;

/**
 * Class BaseController
 * @package Truelab\KottiFrontendBundle\Controller
 */
class BaseController extends Controller
{
    /**
     * @param NodeInterface $context
     *
     * @return string
     */
    protected function path(NodeInterface $context)
    {
        return $this->container->get('truelab_kotti_frontend.template_api')->path($context);
    }
}
