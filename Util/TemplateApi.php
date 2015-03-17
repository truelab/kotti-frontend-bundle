<?php

namespace Truelab\KottiFrontendBundle\Util;

use MIP\CoreBundle\Model\Link;
use Truelab\KottiModelBundle\Model\NodeInterface;
use Truelab\KottiFrontendBundle\Services\CurrentContext;

/**
 * Class TemplateApi
 * @package Truelab\KottiFrontendBundle\Util
 */
class TemplateApi
{
    protected $config;
    protected $lineage;
    protected $options;

    /**
     * @var PathHandlerInterface[]
     */
    protected $pathHandlers = [];

    public function __construct($config, CurrentContext $currentContext, $options = [])
    {
        $this->config = $config;
        $this->currentContext = $currentContext;
        $this->options = $options;
    }

    public function addPathHandler(PathHandlerInterface $pathHandler)
    {
        $this->pathHandlers[] = $pathHandler;
    }

    public function path($context, $parameters = [])
    {
        $url = null;

        if (is_string($context)) {
            $url = $this->frontendDomain($context);
        }

        foreach($this->pathHandlers as $pathHandler) {
            if($pathHandler->support($context)) {
                $url = $pathHandler->getPath($context, $this);
                break;
            }
        }

        if($url === null) {
            if ($context instanceof NodeInterface) {
                $url = $this->frontendDomain($context->getPath());
            }else{
                throw new \RuntimeException(sprintf('I can\'t generate a url for "%s"', get_class($context)));
            }
        }

        // add a query string if needed
        $extra = $parameters; //FIXME
        if ($extra && $query = http_build_query($extra, '', '&')) {
            // "/" and "?" can be left decoded for better user experience, see
            // http://tools.ietf.org/html/rfc3986#section-3.4
            $url .= '?'.strtr($query, array('%2F' => '/'));
        }

        return $url;
    }

    public function imagePath($context, array $options = [])
    {
        if(is_string($context)) {

            $path = $this->imageDomain($context);

        }elseif($context instanceof NodeInterface) {

            $path = $this->imageDomain($context->getPath());
        }else{

            throw new \RuntimeException(sprintf('I can\'t generate a image url for "%s"', get_class($context)));
        }

        $path = rtrim($path, '/') . '/image';

        if(isset($options['span'])) {
            $path = $path . '/span' . $options['span'];
        }

        return $path;
    }

    public function filePath($context, array $options = [])
    {
        $opt = array_merge([
            'download' => true
        ], $options);

        if(!$context) {
            return $context;
        }

        if(is_string($context)) {
            return $context;
        }

        if($context instanceof NodeInterface) {
            $url = $this->imageDomain($context->getPath());

            if($opt['download']) {
                $url = rtrim($url, '/') . '/@@attachment-view';
            }

            return $url;

        }else{
            throw new \RuntimeException(sprintf('I can\'t generate a file url for "%s"', get_class($context)));
        }
    }

    public function getConfig()
    {
        return $this->config;
    }

    protected function getContext()
    {
        return $this->currentContext->get();
    }

    public function navigationRoot()
    {
        return $this->currentContext->navigationRoot();
    }

    public function root()
    {
        return $this->currentContext->root();
    }

    /**
     * @return NodeInterface[]
     */
    public function lineage()
    {
        return $this->currentContext->lineage();
    }

    public function breadcrumbs()
    {

        $breadcrumbs = array_reverse($this->lineage());
        $context = $this->currentContext->get();

        if(!$context || $context->equals($this->navigationRoot())) {
            return [];
        }

        $index = null;
        foreach($breadcrumbs as $i => $breadcrumb)
        {
            if($breadcrumb->equals($this->navigationRoot())) {
                $index = $i;
                break;
            }
        }

        array_push($breadcrumbs, $context);

        if($index !== null) {
            return array_slice($breadcrumbs, $index);
        }



        return $breadcrumbs;
    }

    public function getTypeClass(NodeInterface $context)
    {
        return str_replace('_', '-', $context->getType());
    }

    public function isActiveLink(NodeInterface $link)
    {
        $context = $this->getContext();
        return $context && $this->startsWith($context->getPath(), $link->getPath());
    }

    public function activeLinkClass(NodeInterface $link)
    {
        return $this->isActiveLink($link) ? 'active' : '';
    }

    /**
     * @param $key
     * @param null $default
     *
     * @return mixed|null
     */
    public function option($key, $default = null)
    {
        if(isset($this->options[$key])) {
            return $this->options[$key];
        }
        return $default;
    }

    public function frontendDomain($path)
    {
        return rtrim($this->config['domain'], '/') . $path;
    }

    public function imageDomain($path)
    {
        return rtrim($this->config['image_domain'], '/') . $path;
    }

    protected static function startsWith($haystack, $needle, $case = false)
    {
        if ($case) {
            return strpos($haystack, $needle, 0) === 0;
        }
        return stripos($haystack, $needle, 0) === 0;
    }
}
