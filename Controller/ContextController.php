<?php

namespace Truelab\KottiFrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Truelab\KottiModelBundle\Model\Document;
use Truelab\KottiModelBundle\Model\LanguageRoot;
use Truelab\KottiModelBundle\Model\NodeInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Truelab\KottiModelBundle\Repository\RepositoryInterface;

class ContextController extends Controller
{
    public function __construct()
    {
        // FIXME hardcoded
        $this->currentViewBundle = '@TruelabKottiFrontendBundle';
        $this->currentLayout = '@MIPCoreBundle/Resources/views/base_layout.html.twig';
        $this->viewConfig = [
            Document::getClass() => 'Document:index',
            LanguageRoot::getClass() => 'Home:index'
        ];
    }

    /**
     * @return RedirectResponse
     */
    public function homeAction()
    {
        return new RedirectResponse(
            $this->getLanguage()->getDefaultLanguageRootPath()
        );
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
     * @return \Truelab\KottiMultilanguageBundle\Util\Language
     */
    protected function getLanguage()
    {
        return $this->container->get('truelab_kotti_multilanguage.util.language');
    }



    // FIXME context templating service

    /**
     * @param NodeInterface $context
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function viewLookUp(NodeInterface $context, $parameters = array())
    {
        $parameters = array_merge(array(
            'context' => $context,
            'layout' => $this->currentLayout
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

