<?php

namespace Truelab\KottiFrontendBundle\Controller;

use Truelab\KottiModelBundle\Model\NodeInterface;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class ContextController extends BaseController
{
    /**
     * @param NodeInterface $context
     *
     * @return \Symfony\Component\HttpFoundation\Response
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

