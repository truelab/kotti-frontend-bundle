<?php

namespace Truelab\KottiFrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Truelab\KottiModelBundle\Model\Document;
use Truelab\KottiModelBundle\Model\DocumentInterface;
use Truelab\KottiModelBundle\Model\LanguageRoot;
use Truelab\KottiModelBundle\Model\LanguageRootInterface;
use Truelab\KottiModelBundle\Model\NodeInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class NodeController extends Controller
{
    public function __construct()
    {
        $this->currentViewBundle = '@TruelabKottiFrontendBundle';
        $this->viewConfig = [
            Document::getClass() => 'Document:index',
            LanguageRoot::getClass() => 'Home:index'
        ];
    }

    /**
     * @param NodeInterface $context
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ParamConverter(converter="node_path_converter")
     */
    public function homeAction(NodeInterface $context)
    {
        return $this->viewLookUp($context);
    }

    /**
     * @param NodeInterface $context
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ParamConverter(converter="node_path_converter")
     */
    public function getAction(NodeInterface $context)
    {
        return $this->viewLookUp($context);
    }

    /**
     * @param NodeInterface $context
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function viewLookUp(NodeInterface $context, $parameters = array())
    {
        $parameters = array_merge(array(
            'context' => $context,
            'layout' => $this->getTemplatePath('base')
        ), $parameters);

        return $this->renderContextView($context, $parameters);
    }

    protected function renderContextView(NodeInterface $context, $parameters)
    {
        if(!isset($this->viewConfig[get_class($context)])) {
            throw new \RuntimeException(sprintf('I can\'t handle view lookUp for %s at %s', get_class($context), $context->getPath()));
        }

        return $this->render(
            $this->getTemplatePath($this->viewConfig[get_class($context)]),
            $parameters
        );
    }

    /**
     * @param string $template
     *
     * @return string
     */
    protected function getTemplatePath($template)
    {
        return $this->currentViewBundle
            . '/Resources/views/'
            . str_replace(':' , '/' , $template) . '.html.twig';
    }

}

