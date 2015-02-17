<?php

namespace Truelab\KottiFrontendBundle\Services;
use Symfony\Component\CssSelector\Node\NodeInterface;
use Symfony\Component\HttpFoundation\Request;
use Truelab\KottiFrontendBundle\ParamConverter\NodePathParamConverter;
use Truelab\KottiModelBundle\Exception\NodeByPathNotFoundException;
use Truelab\KottiModelBundle\Repository\RepositoryInterface;


/**
 * Class ContextFromRequest
 * @package Truelab\KottiFrontendBundle\Services
 */
class ContextFromRequest
{
    private $currentContext;
    private $repository;

    public function __construct(RepositoryInterface $repository, CurrentContext $currentContext)
    {
        $this->repository = $repository;
        $this->currentContext = $currentContext;
    }

    public function find(Request $request)
    {
        $path = $request->getPathInfo();
        $data = $this->dataFromPathInfo($path);

        if(!$data['context']) {
            return null;
        }
        // set current context
        $this->currentContext->set($data['context']);

        return $data;
    }

    protected function dataFromPathInfo($pathInfo)
    {
        $segments = explode('/', $pathInfo);

        if($segments[count($segments) - 1] === '') {
            unset($segments[count($segments) - 1]);
        }

        if($segments[0] === '') {
            array_shift($segments);
        }

        $segments = array_map(function ($segment) {
            return '/' . $segment . '/';
        }, $segments);

        array_unshift($segments, '/');

        $paths = [];
        foreach($segments as $i => $segment) {
            if($i > 0) {
                $path =  array_reduce(array_slice($segments, 0, $i), function($path, $s) {
                        return $path . $s;
                    },'') . $segment;
                $path = preg_replace('/\/+/', '/', $path);
                $paths[$segment] = $path;
            }else{
                $paths[$segment] = $segment;
            }
        }

        $data = [
            'lineage' => [],
            'context' => null,
            'action'  => 'view'
        ];

        foreach($paths as $segment => $path) {

            try {

                $node = $this->repository->findByPath($path);
                $data['lineage'][] = $node;
                $data['context']   = $node;

            } catch (NodeByPathNotFoundException $e) {

                $data['action'] = ltrim(rtrim($segment, '/'), '/');
                return $data;
            }
        }


        return $data;
    }

}
