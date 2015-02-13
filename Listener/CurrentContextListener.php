<?php

namespace Truelab\KottiFrontendBundle\Listener;
use Truelab\KottiFrontendBundle\ParamConverter\NodePathParamConverter;
use Truelab\KottiFrontendBundle\Services\ContextFromRequest;
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

    public function __construct(ContextFromRequest $contextFromRequest,
                                CurrentContext $currentContext,
                                \Twig_Environment $twig,
                                $defaultLayout)
    {
        $this->paramName = 'nodePath'; // FIXME
        $this->contextFromRequest = $contextFromRequest;
        $this->currentContext = $currentContext;
        $this->twigEnv = $twig;
        $this->defaultLayout = $defaultLayout;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {

        $this->twigEnv->addGlobal('layout', $this->defaultLayout);

        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $request = $event->getRequest();

        if ($request->attributes->has('context')) {
            // set a global twig variables
            $this->twigEnv->addGlobal('context', $this->currentContext->get());
            return;
        }

        $data = $this->contextFromRequest->find($request);

        if ($data) {

            $context = $data['context'];

            // set current context
            $this->currentContext->set($context);

            // set a request attributes
            $request->attributes->set('context', $this->currentContext->get());

            // set a global twig variables
            $this->twigEnv->addGlobal('context', $this->currentContext->get());
        }



    }

}
