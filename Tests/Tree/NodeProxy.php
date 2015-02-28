<?php

namespace MIP\CoreBundle\Services\Tree;
use Truelab\KottiModelBundle\Model\NodeInterface;

/**
 * Class Node
 * @package MIP\CoreBundle\Services\Tree
 */
class NodeProxy implements NodeInterface
{
    private $node;
    private $children = [];


    public function __construct(NodeInterface $node)
    {
        $this->node = $node;
    }

    public function getId()
    {
        return $this->node->getId();
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->node->getType();
    }

    public function addChild($child) {
        $this->children[] = $child;
    }

    /**
     * @return boolean
     */
    public function hasChildren()
    {
        return count($this->children) === 0;
    }

    public function setChildren($children)
    {
        $this->children = $children;
    }

    /**
     * @return self[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return boolean
     */
    public function hasParent()
    {
        return $this->node->hasParent();
    }


    /**
     * @return self
     */
    public function getParent()
    {
        return $this->node->getParent();
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->node->getPath();
    }

    /**
     * @return NodeInterface[]
     */
    public function getSiblings()
    {
        return $this->node->getSiblings();
    }

    /**
     * @return boolean
     */
    public function isLeaf()
    {
        return count($this->children) === 0;
    }

    /**
     * @param NodeInterface $node
     *
     * @return boolean
     */
    public function equals(NodeInterface $node)
    {
        return $this->node->equals($node);
    }

    public function __call($method, $args)
    {
        if(strpos($method, 'get') === 0 || strpos($method, 'is') === 0)
        {
            if(method_exists($this->node, $method)) {
                return $this->node->{$method}();
            }else{
                throw new \Exception(sprintf('Not existing method on the underlying node object called. "%s" method was called.', $method));
            }

        }else{

            if(method_exists($this->node, $method)) {
                throw new \Exception(sprintf('You cant call a non getter method on the underlying node object. %s method was called.', $method));
            }else{
                return $this->node[$method];
            }
        }
    }

    /**
     * @return array
     */
    public function getAnnotations()
    {
        return $this->node->getAnnotations();
    }
}
