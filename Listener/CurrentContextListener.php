<?php

namespace Truelab\KottiFrontendBundle\Listener;
use Truelab\KottiFrontendBundle\ParamConverter\NodePathParamConverter;
use Truelab\KottiModelBundle\Exception\NodeByPathNotFoundException;
use Truelab\KottiModelBundle\Repository\RepositoryInterface;
use Truelab\KottiFrontendBundle\Services\CurrentContext;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Class CurrentContextRequestListener
 * @package Truelab\KottiFrontendBundle\Listener
 */
class CurrentContextListener
{

    public function __construct(RepositoryInterface $repository, CurrentContext $currentContext, \Twig_Environment $twig)
    {
        $this->repository = $repository;
        $this->currentContext = $currentContext;
        $this->twigEnv = $twig;
        $this->paramName = 'nodePath'; // FIXME
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $request = $event->getRequest();

        if (!$request->attributes->has($this->paramName)) {
            return;
        }

        $path = $request->attributes->get($this->paramName);

        try {

            $nodePath = NodePathParamConverter::sanitizeNodePathParam($path);

            $node = $this->repository->findByPath($nodePath);

            // set current context
            $this->currentContext->set($node);

            // set a request attributes
            $request->attributes->set('context', $this->currentContext->get());

            // set a global twig variables
            $this->twigEnv->addGlobal('context', $this->currentContext->get());

        } catch (NodeByPathNotFoundException $e) {
            return;
        }
    }

}
