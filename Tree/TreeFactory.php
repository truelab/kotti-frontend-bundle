<?php

namespace Truelab\KottiFrontendBundle\Tree;

use Truelab\KottiModelBundle\Model\NodeInterface;



/**
 * Class TreeFactory
 * @package MIP\CoreBundle\Services\TreeNode
 */
class TreeFactory implements TreeFactoryInterface
{
    protected static $nodeProxyClass   = 'Truelab\KottiFrontendBundle\Tree\NodeProxy';

    /**
     * @param NodeInterface[]|NodeInterface $nodes
     * @param callable $getChildren
     *
     * @return NodeProxy[]|NodeProxy
     * @throws \Exception
     */
    public static function createProxy($nodes, callable $getChildren)
    {
        if (is_array($nodes)) {
            return self::createProxyFromArray($nodes, $getChildren);
        }

        if ($nodes instanceof NodeInterface) {
            return self::createProxyFromNode($nodes, $getChildren);
        }

        throw new \Exception(sprintf('$node param can be an array (NodeInterface[]) or an instance of a NodeInterface. "%s" given.', gettype($nodes)));
    }

    /**
     * @param array $nodes
     * @param callable $getChildren
     *
     * @return NodeProxy[]
     */
    protected static function createProxyFromArray(array $nodes, callable $getChildren)
    {
        $treeNodes = [];
        foreach($nodes as $node) {
            $treeNodes[] = self::createProxyFromNode($node, $getChildren);
        }
        return $treeNodes;
    }

    /**
     * @param NodeInterface $node
     * @param callable $getChildren
     *
     * @return NodeProxy
     */
    protected static function createProxyFromNode(NodeInterface $node, callable $getChildren)
    {
        $treeNode = self::createNode($node);
        $treeNode->setChildren(TreeFactoryObject::executeCallback($getChildren, $node));
        return $treeNode;
    }

    /**
     * @param NodeInterface $node
     * @param callable $getChildren
     * @param int $maxDepth
     *
     * @return \Tree\Node\NodeInterface
     */
    public static function getTree(NodeInterface $node, callable $getChildren, $maxDepth = 10)
    {
        return (new TreeFactoryObject($node, $getChildren))->getTree($maxDepth);
    }

    /**
     * @param array $lineage
     * @param $context
     *
     * @return \Tree\Node\NodeInterface
     */
    public static function getLineageTree(array $lineage, $context)
    {
        $builder = TreeFactoryObject::createTreeBuilder();
        $reverseLineage = array_reverse($lineage);

        array_push($reverseLineage, $context);

        if(count($reverseLineage) === 1) {
            $builder->value($reverseLineage[0]);
            return $builder->getNode();
        }

        foreach($reverseLineage as $index => $node) {
            if($index === 0) {
                $builder->value($node);
            }

            if($index > 0 && $index <= count($lineage) - 1) {
                $builder->tree($node);
            }

            if($index > 0 && $index === count($lineage)) {
                $builder->leaf($node);
            }
        }

        return $builder->getNode()->getChildren()[0];
    }

    /**
     * @param NodeInterface $node
     *
     * @return NodeProxy
     */
    protected static function createNode(NodeInterface $node)
    {
        return new self::$nodeProxyClass($node);
    }

}

