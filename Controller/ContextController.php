<?php

namespace Truelab\KottiFrontendBundle\Controller;

use Truelab\KottiModelBundle\Model\NodeInterface;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class ContextController extends BaseController
{
    /**
     * @param NodeInterface $context
     *
     * @return array
     *
     * @Template()
     */
    public function viewAction(NodeInterface $context)
    {
        return [
            'context' => $context
        ];
    }
}

