<?php



namespace Truelab\KottiFrontendBundle\BodyProcessor\Html;
use Sunra\PhpSimple\HtmlDomParser;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class Image
 * @package Truelab\KottiFrontendBundle\BodyProcessor\Html
 */
class ImageBodyProcessor implements BodyProcessorInterface
{
    private $imageDomain;

    public function __construct($imageDomain = 'http://localhost:5000')
    {
        if(!$imageDomain) {
            throw new Exception('image_domain can not be null.');
        }

        $this->imageDomain = $imageDomain;
    }

    /**
     * @param $input
     *
     * @return string
     */
    public function process($input)
    {
        $html = HtmlDomParser::str_get_html($input);
        foreach($html->find('img') as $img) {
            $img->setAttribute('src', $this->imageDomain . $img->getAttribute('src'));
        }
        return $html->__toString();
    }
}
