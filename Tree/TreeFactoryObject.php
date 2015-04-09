<?php

namespace Truelab\KottiFrontendBundle\Tree;
use Tree\Builder\NodeBuilderInterface;
use Truelab\KottiFrontendBundle\Tree\Exception\GetChildrenCallbackException;
use Truelab\KottiModelBundle\Model\NodeInterface;

/**
 * Class TreeFactoryObject
 * @package Truelab\KottiFrontendBundle\Tree
 */
class TreeFactoryObject implements TreeFactoryObjectInterface {

    /**
     * @var NodeInterface
     */
    private $root;

    /**
     * @var callable
     */
    private $callable;

    /**
     * @var NodeBuilderInterface
     */
    private $treeBuilder;

    /**
     * @var string
     */
    protected static $nodeBuilderClass = 'Tree\Builder\NodeBuilder';

    public function __construct(NodeInterface $root, callable $getChildren)
    {
        $this->root = $root;
        $this->callable = $getChildren;
    }

    /**
     * @param int $maxDepth
     *
     * @return \Tree\Node\NodeInterface
     */
    public function getTree($maxDepth = 1000)
    {
        $this->treeBuilder = self::createTreeBuilder();

        $array = [];
        $this->traverse($this->root, $maxDepth, $array, $this->treeBuilder);

        $i = 0;
        foreach($array as $node) {

            if($i === 0) {

                // the root
                $this->treeBuilder->value($node['node']);

            }else{
                $parentDepth  = $node['parent']['depth'];
                $builderDepth = $this->treeBuilder->getNode()->getDepth();

                // rewind to the right builder depth
                if($parentDepth !== $builderDepth) {
                    while($parentDepth !== $this->treeBuilder->getNode()->getDepth()) {
                        $this->treeBuilder->end();
                    }
                }

                if($node['has_children']) {
                    $this->treeBuilder->tree($node['node']);
                }else{
                    $this->treeBuilder->leaf($node['node']);
                }
            }

            $i++;
        }

        while($this->treeBuilder->getNode()->getParent()) {
            $this->treeBuilder->end();
        }

        return $this->treeBuilder->getNode();
    }

    /**
     * @param NodeInterface $parent
     * @param null $maxDepth
     * @param $array
     * @param $builder
     * @param int $depth
     *
     * @throws GetChildrenCallbackException
     * @throws \Exception
     */
    private function traverse(NodeInterface $parent, $maxDepth = null, &$array, &$builder, $depth = 0)
    {
        $children = self::executeCallback($this->callable, $parent);

        if(count($array) === 0) {
            $array[$parent->getPath()] = [
                'path' => $parent->getPath(),
                'depth' => $depth,
                'has_children' => count($children) > 0,
                'id' => $parent->getId(),
                'node' => $parent,
                'parent' => []
            ];
        }

        if(count($children) > 0) {
            $depth++;
        }

        if(is_int($maxDepth) && ($maxDepth + 1) === $depth) {
            return;
        }

        /**
         * @var NodeInterface $child
         */
        foreach($children as $child)
        {
            if(!$child instanceof NodeInterface) {
                throw new \Exception('child must be an instance of Truelab\KottiModelBundle\Model\NodeInterface!');
            }

            $array[$child->getPath()] = [
                'path' => $child->getPath(),
                'depth' => $depth,
                'has_children' => count($children) > 0,
                'id' => $child->getId(),
                'node' => $child,
                'parent' => [
                    'path' => $parent->getPath(),
                    'depth' => $depth - 1,
                    'id' => $parent->getId()
                ]
            ];

            $this->traverse($child,  $maxDepth, $array, $builder, $depth);
        }
    }

    /**
     * @param callable $callback
     * @param NodeInterface $node
     *
     * @return array
     * @throws GetChildrenCallbackException
     */
    public static function executeCallback(callable $callback, NodeInterface $node)
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
     * @return NodeBuilderInterface
     */
    public static function createTreeBuilder()
    {
        return new self::$nodeBuilderClass();
    }
}

