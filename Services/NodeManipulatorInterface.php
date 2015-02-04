<?php

namespace Truelab\KottiFrontendBundle\Services;

use Truelab\KottiORMBundle\Model\NodeInterface;

interface NodeManipulatorInterface
{
    public function getParents(NodeInterface $node, $deep = false);

    public function getParentsReversed(NodeInterface $node, $deep = false);
}
