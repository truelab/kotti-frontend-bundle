<?php

namespace Truelab\KottiFrontendBundle\Tree;
use Truelab\KottiModelBundle\Model\NodeInterface;

/**
 * Interface TreeFactoryInterface
 * @package Truelab\KottiFrontendBundle\Tree
 */
interface TreeFactoryInterface
{
    /**
     * @param NodeInterface[]|NodeInterface $nodes
     * @param callable $getChildren
     *
     * @return NodeProxy[]|NodeProxy
     */
    public static function createProxy($nodes, callable $getChildren);

    /**
     * @param NodeInterface $node
     * @param callable $getChildren
     * @param int $maxDepth
     *
     * @return \Tree\Node\NodeInterface
     */
    public static function getTree(NodeInterface $node, callable $getChildren, $maxDepth = 10);

    /**
     * @param array $lineage
     *
     * @param $context
     *
     * @return \Tree\Node\NodeInterface
     */
    public static function getLineageTree(array $lineage, $context);
}
