<?php

namespace Truelab\KottiFrontendBundle\BodyProcessor\Html;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class Image
 * @package Truelab\KottiFrontendBundle\BodyProcessor\Html
 */
class ImageBodyProcessor implements BodyProcessorInterface
{
    private $mediaBaseUrl;

    public function __construct($mediaBaseUrl = 'http://localhost:5000')
    {
        if(!$mediaBaseUrl) {
            throw new Exception('image_domain can not be null.');
        }

        $this->mediaBaseUrl = $mediaBaseUrl;
    }

    /**
     * @param $html
     *
     * @return string
     */
    public function process($html)
    {
        foreach($html->find('img') as $img) {
            $img->setAttribute('src', $this->mediaBaseUrl . $img->getAttribute('src'));
        }

        foreach($html->find('img[style="float: right;"]') as $imgRight) {
            //$imgRight->removeAttribute('style');
            $imgRight->setAttribute('class', 'img-right');
        }

        foreach($html->find('img[style="float: left;"]') as $imgRight) {
            //$imgRight->removeAttribute('style');
            $imgRight->setAttribute('class', 'img-left');
        }

        return $html;
    }
}
