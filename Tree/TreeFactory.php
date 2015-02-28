<?php
namespace Truelab\KottiFrontendBundle\Tree;


use Tree\Builder\NodeBuilderInterface;
use Truelab\KottiModelBundle\Model\NodeInterface;
use Truelab\KottiFrontendBundle\Tree\Exception\GetChildrenCallbackException;


/**
 * Class TreeFactory
 * @package MIP\CoreBundle\Services\TreeNode
 */
class TreeFactory implements  TreeFactoryInterface
{
    protected static $nodeBuilderClass = 'Tree\Builder\NodeBuilder';

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

        throw new \Exception(sprintf('$node param can be an array (NodeInterface[]) or an instance of a NodeInterface. "%s" given.', get_class($node)));
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
        $treeNode->setChildren(self::executeCallback($getChildren, $node));
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
        return self::getTreeBuilder($node, $getChildren, $maxDepth)->getNode();
    }

    /**
     * @param NodeInterface $node
     * @param callable $getChildren
     * @param int $maxDepth
     *
     * @return NodeBuilderInterface
     */
    protected static function getTreeBuilder(NodeInterface $node, callable $getChildren, $maxDepth = 10)
    {
        /**
         * @var NodeBuilderInterface $builder
         */
        $builder = self::createTreeBuilder();
        $rootNodes = [];

        self::traverseTree(self::executeCallback($getChildren, $node), $getChildren, $maxDepth, $rootNodes, $node, $builder);

        return $builder;
    }

    /**
     * @param array $children
     * @param callable $getChildren
     * @param int $maxDepth
     *
     * utility recursive params:
     *
     * @param array $rootNodes
     * @param NodeInterface $node
     * @param NodeBuilderInterface $builder
     * @param int $depth
     */
    protected static function traverseTree(array $children, callable $getChildren, $maxDepth, &$rootNodes = [], NodeInterface $node, NodeBuilderInterface &$builder, $depth = 0)
    {

        if($maxDepth === $depth) {
            return;
        }

        $depth++;

        /**
         * @var $child NodeInterface
         */
        foreach($children as $child)
        {

            if(!isset($rootNodes[$node->getPath()])) {
                $rootNodes[$node->getPath()] = $node;

                // THIS IS THE ROOT NODE!
                if($depth === 1) {
                    $builder->value($node);
                }else{
                    // IS EVENTUALLY A WRONG PLACE FOR A NEW TREE NODE?
                    if(($depth - 1) !== ($builder->getNode()->getDepth() + 1)) {
                        while(($depth - 1) !== $builder->getNode()->getDepth()) {
                            $builder->end();
                        }
                    }
                    $builder->tree($node);
                }

            }

            // IS EVENTUALLY A WRONG PLACE FOR A LEAF?
            if(($depth - 1) !== ($builder->getNode()->getDepth())) {
                while(($depth - 1) !== $builder->getNode()->getDepth()) {
                    $builder->end();
                }
            }

            // IF IS NOT FATHER IS A LEAF
            if(count($childChildren = self::executeCallback($getChildren, $child)) === 0) {
                $builder->leaf($child);
            }

            self::traverseTree(
                $childChildren,
                $getChildren,
                $maxDepth,
                $rootNodes,
                $child,
                $builder,
                $depth
            );
        }
    }

    /**
     * @return NodeBuilderInterface
     */
    protected static function createTreeBuilder()
    {
        return new self::$nodeBuilderClass();
    }

    /**
     * @param callable $callback
     * @param NodeInterface $node
     *
     * @return \Truelab\KottiModelBundle\Model\NodeInterface[]
     *
     * @throws GetChildrenCallbackException
     */
    protected static function executeCallback(callable $callback, NodeInterface $node)
    {
        $array = call_user_func_array($callback, [
            $node
        ]);

        if(!is_array($array)) {
            throw new GetChildrenCallbackException(
               sprintf('Get children callback must return an array of nodes! "%s" given.', gettype($array))
            );
        }
        return $array;
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
