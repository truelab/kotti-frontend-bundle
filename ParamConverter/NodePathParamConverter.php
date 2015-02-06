<?php

namespace Truelab\KottiFrontendBundle\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Truelab\KottiModelBundle\Exception\NodeByPathNotFoundException;
use Truelab\KottiModelBundle\Repository\RepositoryInterface;


/**
 * Class NodePathParamConverter
 * @package Truelab\KottiFrontendBundle\ParamConverter
 */
class NodePathParamConverter implements ParamConverterInterface
{
    /**
     * @var RepositoryInterface $repository
     */
    private $repository;

    /**
     * @var AuthorizationCheckerInterface $authorizationChecker
     */
    private $authorizationChecker;

    /**
     * Stores the object in the request.
     *
     * @param Request $request The request
     * @param ParamConverter $configuration Contains the name, class and options of the object
     *
     * @return bool true if the object has been successfully set, else false
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $paramName = 'nodePath'; // FIXME get options
        $nodePathParam = $request->attributes->get($paramName, false);

        if(!$nodePathParam) {
            throw new \RuntimeException(
                sprintf(
                    '"%s" param not found in current request, you can\'t use "%s" without that!',
                    $paramName,
                    get_class($this)
                )
            );
        }

        // find by path
        try {
            $nodePath = $this->sanitizeNodePathParam($nodePathParam);
            $node = $this->getRepository()->findByPath($nodePath);
        } catch (NodeByPathNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage(), $e);
        }

        if($this->authorizationChecker->isGranted('VIEW', $node) !== true) {
            throw new HttpException(403, sprintf('Requested node at path = "%s" is "%s"', $node['path'], $node['state']));
        }

        $request->attributes->set($configuration->getName(), $node);

        return true;
    }

    /**
     * Checks if the object is supported.
     *
     * @param ParamConverter $configuration Should be an instance of ParamConverter
     *
     * @return bool True if the object is supported, else false
     */
    public function supports(ParamConverter $configuration)
    {
        return true;
    }

    /**
     * FIXME this code sucks
     * @param string $path
     * @return string
     */
    public function sanitizeNodePathParam($path)
    {
        $path = trim($path);

        if($path == "''") {
           return '/';
        }else{
            $path = rtrim($path, '/') . '/';
            $path = '/' .ltrim($path, '/');
            $path = strtolower($path);
        }

        return $path;
    }

    /**
     * @param RepositoryInterface $repository
     */
    public function setRepository(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return RepositoryInterface
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function setAuthorizationChecker(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @return AuthorizationCheckerInterface
     */
    public function getAuthorizationChecker()
    {
        return $this->authorizationChecker;
    }
}
