<?php

namespace Truelab\KottiFrontendBundle\Tree;
use Truelab\KottiFrontendBundle\Tree\Exception\GetChildrenCallbackException;
use Truelab\KottiModelBundle\Model\NodeInterface;
use Tree\Builder\NodeBuilderInterface;

/**
 * Interface TreeFactoryObject
 * @package Truelab\KottiFrontendBundle\Tree
 */
interface TreeFactoryObjectInterface {

    /**
     * @param int $maxDepth
     *
     * @return \Tree\Node\NodeInterface
     */
    public function getTree($maxDepth = 1000);

    /**
     * @param callable $callback
     * @param NodeInterface $node
     *
     * @return array
     * @throws GetChildrenCallbackException
     * @throws \Exception
     */
    public static function executeCallback(callable $callback, NodeInterface $node);

    /**
     * @return NodeBuilderInterface
     */
    public static function createTreeBuilder();
}

