<?php

namespace Truelab\KottiFrontendBundle\Tests\BodyProcessor\Html;
use Sunra\PhpSimple\HtmlDomParser;
use Truelab\KottiFrontendBundle\BodyProcessor\Html\FileAttachmentBodyProcessor;
use Truelab\KottiFrontendBundle\BodyProcessor\Html\ImageBodyProcessor;

/**
 * Class FileAttachmentBodyProcessorTest
 * @package Truelab\KottiFrontendBundle\Tests\BodyProcessor\Html
 */
class FileAttachmentBodyProcessorTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $templateApi = $this
            ->getMockBuilder('Truelab\KottiFrontendBundle\Util\TemplateApi')
            ->disableOriginalConstructor()
            ->getMock();

        $templateApi
            ->expects($this->any())
            ->method('mediaBaseUrl')
            ->will($this->returnCallback(function($string) { return 'http://localhost:8000' . $string; }));

        $html = HtmlDomParser::str_get_html('Hello! <a title="Download brochure" href="/brochure/brochure-international-part-time-mba/@@attachment-view">Download brochure</a> <a href="/normal@@attachment_view/link"></a>');
        $processor = new FileAttachmentBodyProcessor($templateApi);

        $expected = 'Hello! <a title="Download brochure" href="http://localhost:8000/brochure/brochure-international-part-time-mba/@@attachment-view">Download brochure</a> <a href="/normal@@attachment_view/link"></a>';
        $this->assertEquals($expected, $processor->process($html)->__toString());
    }
}
