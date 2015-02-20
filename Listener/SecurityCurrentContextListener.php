<?php

namespace Truelab\KottiFrontendBundle\Listener;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Truelab\KottiFrontendBundle\ParamConverter\NodePathParamConverter;
use Truelab\KottiFrontendBundle\Services\ContextFromRequest;
use Truelab\KottiModelBundle\Exception\NodeByPathNotFoundException;
use Truelab\KottiModelBundle\Repository\RepositoryInterface;
use Truelab\KottiFrontendBundle\Services\CurrentContext;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Class SecurityCurrentContextListener
 * @package Truelab\KottiFrontendBundle\Listener
 */
class SecurityCurrentContextListener
{
    /**
     * @var AuthorizationCheckerInterface $authorizationChecker
     */
    private $authorizationChecker;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var CurrentContext
     */
    private $currentContext;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker,
                                TokenStorageInterface $tokenStorage,
                                CurrentContext $currentContext)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->currentContext = $currentContext;
        $this->tokenStorage = $tokenStorage;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        // no context is set
        if(!$context = $this->currentContext->get()) {
            return;
        }

        // no token is set, it means that this route is not under a firewall
        if(!$this->tokenStorage->getToken()) {
            return;
        }


        if($this->authorizationChecker->isGranted('VIEW', $context) !== true) {
            throw new HttpException(403, sprintf('Requested context at path = "%s" is marked as "%s"', $context['path'], $context['state']));
        }

    }
}
