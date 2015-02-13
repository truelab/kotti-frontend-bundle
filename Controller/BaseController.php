<?php

namespace Truelab\KottiFrontendBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Truelab\KottiModelBundle\Model\NodeInterface;
use Truelab\KottiModelBundle\Model\Document;
use Truelab\KottiMultilanguageBundle\Model\LanguageRoot;

/**
 * Class BaseController
 * @package Truelab\KottiFrontendBundle\Controller
 */
class BaseController extends Controller
{
    protected $templatesConfig = array();

    public function __construct()
    {
        // FIXME hardcoded
        $this->templatesConfig['theme_bundle'] = '@TruelabKottiFrontendBundle';
        $this->templatesConfig['theme_layout'] = '@MIPCoreBundle/Resources/views/base_layout.html.twig';
        $this->templatesConfig['theme_templates_map'] = [
            Document::getClass() => 'Document:index'
        ];
    }

    /**
     * @param NodeInterface $context
     *
     * @param array $parameters
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderTemplate(NodeInterface $context, $parameters = array())
    {
        $parameters = array_merge(array(
            'context' => $context,
            'layout'  => $this->templatesConfig['theme_layout']
        ), $parameters);

        return $this->render(
            $this->getTemplatePath($context),
            $parameters
        );
    }

    /**
     * @param \Truelab\KottiModelBundle\Model\NodeInterface $context
     *
     * @return string
     */
    protected function getTemplatePath(NodeInterface $context)
    {
        $config = $this->templatesConfig['theme_templates_map'];

        if(!isset($config[get_class($context)])) {
            throw new \RuntimeException(sprintf('I can\'t handle view lookUp for %s at %s', get_class($context), $context->getPath()));
        }

        $template = $config[get_class($context)];

        return sprintf(
            '%s/Resources/views/%s.html.twig',
            $this->templatesConfig['theme_bundle'],
            str_replace(':' , '/' , $template)
        );
    }

    /**
     * @param NodeInterface $context
     *
     * @return string
     */
    protected function path(NodeInterface $context)
    {
        return $this->container->get('truelab_kotti_frontend.template_api')->path($context);
    }
}
