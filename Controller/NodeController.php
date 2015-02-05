<?php

namespace Truelab\KottiFrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Truelab\KottiMultilanguageBundle\Entity\LanguageRoot;
use Truelab\KottiORMBundle\Entity\Document;
use Truelab\KottiORMBundle\Model\NodeInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class NodeController extends Controller
{
    // FIXME XXX
    protected $currentViewBundle = 'TruelabKottiFrontendBundle';

    /**
     * @param NodeInterface $node
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ParamConverter(converter="node_path_converter")
     */
    public function homeAction(NodeInterface $node)
    {
        return $this->viewLookUp($node);
    }

    /**
     * @param NodeInterface $node
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ParamConverter(converter="node_path_converter")
     */
    public function getAction(NodeInterface $node)
    {
        return $this->viewLookUp($node);
    }

    /**
     * FIXME XXX
     *
     * @param NodeInterface $node
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewLookUp(NodeInterface $node, $options = array())
    {
        // default parameters
        $options = array_merge(array(
            'context' => $node,
            'layout' => 'TruelabKottiFrontendBundle::base.html.twig' // FIXME
        ), $options);

        if(isset($options['template'])) {
            return $this->render($options['template'], $options);
        }

        if($node instanceof Document) {
            return $this->render(
                $this->getView('Document:index'),
                $options
            );
        }

        if($node instanceof LanguageRoot) {
            return $this->render(
                $this->getView('Home:index'),
                $options
            );
        }

        throw new \RuntimeException(sprintf('I can\'t handle view lookUp for %s', $node->__toString()));
    }

    /**
     * FIXME XXX
     *
     * @param string $template
     *
     * @return string
     */
    public function getView($template)
    {
        return $this->currentViewBundle . ':' . $template . '.html.twig';
    }

}

