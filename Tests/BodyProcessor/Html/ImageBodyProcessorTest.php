<?php

namespace Truelab\KottiFrontendBundle\Tests\BodyProcessor\Html;
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
        $string = '<p> hello! <img src="/some/path/" alt="Some alt" height="200"/> text text <img src="/some/other/path" /></p>';
        $processor = new ImageBodyProcessor($imageDomain);

        $expected = '<p> hello! <img src="http://localhost:8000/some/path/" alt="Some alt" height="200"/> text text <img src="http://localhost:8000/some/other/path" /></p>';
        $this->assertEquals($expected, $processor->process($string));
    }
}
