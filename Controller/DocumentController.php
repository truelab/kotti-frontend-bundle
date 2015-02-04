<?php

namespace Truelab\KottiFrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Truelab\KottiORMBundle\Model\NodeInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class DocumentController extends Controller
{
    /**
     * @param NodeInterface $node
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ParamConverter(converter="node_path_converter")
     */
    public function indexAction(NodeInterface $node)
    {
        return $this->render(
            'TruelabKottiFrontendBundle:Document:index.html.twig',
            array('nodePath' => $node->getPath(), 'node' => $node )
        );
    }
}
