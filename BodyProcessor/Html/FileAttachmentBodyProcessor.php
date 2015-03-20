<?php

namespace Truelab\KottiFrontendBundle\BodyProcessor\Html;
use Truelab\KottiFrontendBundle\Util\TemplateApi;

/**
 * Class Image
 * @package Truelab\KottiFrontendBundle\BodyProcessor\Html
 */
class FileAttachmentBodyProcessor implements BodyProcessorInterface
{
    private $templateApi;

    public function __construct(TemplateApi $templateApi)
    {
        $this->templateApi = $templateApi;
    }

    /**
     * @param $html
     *
     * @return string
     */
    public function process($html)
    {
        foreach($html->find('a') as $link) {
            $href = $link->getAttribute('href');

            if($href && preg_match("/@@attachment-view$/", $href)) {
                $link->setAttribute('href', $this->templateApi->mediaBaseUrl($href));
            }
        }
        return $html;
    }
}
