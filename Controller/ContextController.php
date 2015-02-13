<?php

namespace Truelab\KottiFrontendBundle\Controller;

use Truelab\KottiModelBundle\Model\NodeInterface;


class ContextController extends BaseController
{
    /**
     * @param NodeInterface $context
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(NodeInterface $context)
    {
        return $this->renderTemplate($context);
    }
}

