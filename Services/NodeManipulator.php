<?php

namespace Truelab\KottiFrontendBundle\Services;

use Truelab\KottiORMBundle\Model\NodeInterface;

class NodeManipulator implements NodeManipulatorInterface
{
    public function getParents(NodeInterface $node, $deep = false)
    {
        $flat = [];
        $this->flatParents($node, $flat, $deep);
        return $flat;
    }

    public function getParentsReversed(NodeInterface $node, $deep = false)
    {
        return array_reverse($this->getParents($node, $deep));
    }

    protected function flatParents(NodeInterface $node, &$flat, $deep = false, &$n = 0)
    {
        if($deep !== false && $n >= $deep) {
            return null;
        }

        $parent = $node->getParent();
        if(!$parent instanceof NodeInterface) return null;

        $flat[] = $parent;
        $n = $n + 1;

        return $this->flatParents($parent, $flat, $deep, $n);
    }
}
