<?php

namespace Truelab\KottiFrontendBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Truelab\KottiModelBundle\Model\NodeInterface;


class ContextController extends BaseController
{
    /**
     * @param NodeInterface $context
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ParamConverter(converter="node_path_converter")
     */
    public function getAction(NodeInterface $context)
    {
        return $this->renderTemplate($context);
    }
}

