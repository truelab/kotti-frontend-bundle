<?php

namespace Truelab\KottiFrontendBundle\Tests\BodyProcessor\Html;
use Sunra\PhpSimple\HtmlDomParser;
use Truelab\KottiFrontendBundle\BodyProcessor\Html\ImageBodyProcessor;

/**
 * Class ImageBodyProcessorTest
 * @package Truelab\KottiFrontendBundle\Tests\BodyProcessor\Html
 */
class ImageBodyProcessorTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $imageDomain = 'http://localhost:8000';
        $html = HtmlDomParser::str_get_html('<p> hello! <img src="/some/path/" style="float: right;" alt="Some alt" height="200"/> text text <img src="/some/other/path" /></p>');
        $processor = new ImageBodyProcessor($imageDomain);

        $expected = '<p> hello! <img src="http://localhost:8000/some/path/" style="float: right;" alt="Some alt" height="200" class="img-right"/> text text <img src="http://localhost:8000/some/other/path" /></p>';
        $this->assertEquals($expected, $processor->process($html)->__toString());
    }
}
