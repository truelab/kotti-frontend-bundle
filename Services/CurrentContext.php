<?php

namespace Truelab\KottiFrontendBundle\Services;
use Truelab\KottiModelBundle\Model\NodeInterface;

/**
 * Class CurrentContext
 * @package Truelab\KottiFrontendBundle\Services
 */
class CurrentContext
{
    /**
     * @var NodeInterface
     */
    private $context;

    /**
     * @var NavigationRootChooserInterface
     */
    private $navigationRootChooser;

    /**
     * @var NodeInterface[]
     */
    private $lineage;

    public function __construct(NavigationRootChooserInterface $navigationRootChooser = null)
    {
        $this->navigationRootChooser = $navigationRootChooser;
    }

    /**
     * @param NodeInterface $context
     */
    public function set(NodeInterface $context)
    {
        $this->context = $context;
    }

    /**
     * @return NodeInterface
     */
    public function get()
    {
        return $this->context;
    }

    /**
     * @return NodeInterface
     */
    public function root()
    {
        if(count($this->lineage()) === 0) {
            return $this->get();
        }

        return $this->lineage()[count($this->lineage()) - 1];
    }

    /**
     * @return NodeInterface
     */
    public function navigationRoot()
    {

        if($this->navigationRootChooser) {

            if($this->navigationRootChooser->choose($this->get())) {
                return $this->get();
            }

            foreach($this->lineage() as $node)
            {
                if($this->navigationRootChooser->choose($node)) {
                    return $node;
                }
            }
        }

        return $this->root();
    }

    /**
     * @return \Truelab\KottiModelBundle\Model\NodeInterface[]
     */
    public function lineage()
    {
        if($this->lineage) {
            return $this->lineage;
        }

        $this->lineage = $this->flatParents($this->get());
        return $this->lineage;
    }

    /**
     * @param NodeInterface $node
     * @param array $flat
     *
     * @return NodeInterface[]
     */
    protected function flatParents(NodeInterface $node = null, &$flat = array())
    {
        if(!$node) {
            return $flat;
        }

        if($node->hasParent()) {
            $parent = $node->getParent();
            $flat[] = $parent;
            return $this->flatParents($parent, $flat);
        }else{
            return $flat;
        }
    }
}
