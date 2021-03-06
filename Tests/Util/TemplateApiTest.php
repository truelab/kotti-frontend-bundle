<?php

namespace Truelab\KottiFrontendBundle\Tests\Util;
use Truelab\KottiFrontendBundle\Util\TemplateApi;
use Truelab\KottiModelBundle\Model\NodeInterface;

/**
 * Class TemplateApiTest
 * @package Truelab\KottiFrontendBundle\Tests\Util
 * @group unit
 */
class TemplateApiTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TemplateApi
     */
    protected $api;


    public function testConstructor()
    {
        $currentContext = $this
            ->getMockBuilder('Truelab\KottiFrontendBundle\Services\CurrentContext')
            ->disableOriginalConstructor()
            ->getMock();
        $this->api = new TemplateApi(array(
            'base_url' => 'http://localhost:8000/'
        ), $currentContext);
        $this->assertArrayHasKey('base_url', $this->api->getConfig());
    }

    /**
     * @dataProvider provideDomainPath
     *
     * @param $domain
     * @param $path
     */
    public function testPath($domain, $path)
    {
        $currentContext = $this
            ->getMockBuilder('Truelab\KottiFrontendBundle\Services\CurrentContext')
            ->disableOriginalConstructor()
            ->getMock();

        $this->api = new TemplateApi(array(
            'base_url' => $domain
        ), $currentContext);

        /**
         * @var NodeInterface
         */
        $context = $this
            ->getMock('Truelab\KottiModelBundle\Model\NodeInterface');

        $context->expects($this->any())
            ->method('getPath')
            ->willReturn('/en/mip/');

        $this->assertEquals($path, $this->api->path($context));

        $this->assertEquals($path . '?page=1&color=red', $this->api->path($context, array('page' => 1, 'color'=> 'red')));
    }

    public function testBreadcrumbs()
    {
        $currentContext = $this
            ->getMockBuilder('Truelab\KottiFrontendBundle\Services\CurrentContext')
            ->enableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->getMock();

        $currentContext->set($this->provideNode());

        $this->api = new TemplateApi(array(
            'base_url' => 'http://localhost:8000/'
        ), $currentContext);

        $breadcrumbsPath = array_map(function ($node) {
            return $node->getPath();
        }, $this->api->breadcrumbs());

        $this->assertEquals(['/','/en/', '/en/foo/', '/en/foo/bar/'], $breadcrumbsPath);
    }

    public function testImagePath()
    {
        $currentContext = $this
            ->getMockBuilder('Truelab\KottiFrontendBundle\Services\CurrentContext')
            ->enableProxyingToOriginalMethods()
            ->disableOriginalConstructor()
            ->getMock();

        $currentContext->set($this->provideNode());

        $imageNode = $this->getMock('Truelab\KottiModelBundle\Model\NodeInterface');

        $imageNode
            ->expects($this->any())
            ->method('getPath')
            ->willReturn('/images/foo/bar/');

        $this->api = new TemplateApi(array(
            'media_base_url' => 'http://cms.admin.com/'
        ), $currentContext);

        $this->assertEquals('http://cms.admin.com/images/foo/image', $this->api->imagePath('/images/foo/'));
        $this->assertEquals('http://cms.admin.com/images/foo/image/span1', $this->api->imagePath('/images/foo/', ['span'=>1]));

        $this->assertEquals('http://cms.admin.com/images/foo/bar/image', $this->api->imagePath($imageNode));
        $this->assertEquals('http://cms.admin.com/images/foo/bar/image/span10', $this->api->imagePath($imageNode, ['span'=>10]));
    }

    public function provideDomainPath()
    {
        return [
            ['base_url'=> 'http://localhost:8000/', 'path' => 'http://localhost:8000/en/mip/'],
            ['base_url' => 'http://www.localhost.com', 'path' => 'http://www.localhost.com/en/mip/' ]
        ];
    }

    public function provideCurrentContext()
    {
        return
            $this
                ->getMockBuilder('Truelab\KottiFrontendBundle\Services\CurrentContext')
                ->disableOriginalConstructor()
                ->getMock();
    }

    public function provideNode()
    {
        $class = 'Truelab\KottiModelBundle\Model\NodeInterface';
        $methods = array(
            'hasParent',
            'getParent',
            'getPath',
            'equals',
            'getChildren',
            'hasChildren',
            'setRepository',
            'getId',
            'getType',
            'isLeaf',
            'getSiblings',
            'getAnnotations'
        );


        $leaf    = $this->getMock($class, $methods);
        $parent = $this->getMock($class, $methods);

        $navigationRoot = $this->getMock($class, $methods);
        $root = $this->getMock($class, $methods);

        $root
            ->expects($this->any())
            ->method('hasParent')
            ->willReturn(false);

        $root
            ->expects($this->any())
            ->method('getParent')
            ->willReturn(null);

        $root->expects($this->any())
            ->method('getPath')
            ->willReturn('/');

        $navigationRoot
            ->expects($this->any())
            ->method('hasParent')
            ->willReturn(true);

        $navigationRoot
            ->expects($this->any())
            ->method('getParent')
            ->willReturn($root);

        $navigationRoot
            ->expects($this->any())
            ->method('getPath')
            ->willReturn('/en/');

        $parent
            ->expects($this->any())
            ->method('hasParent')
            ->willReturn(true);

        $parent
            ->expects($this->any())
            ->method('getParent')
            ->willReturn($navigationRoot);

        $parent
            ->expects($this->any())
            ->method('getPath')
            ->willReturn('/en/foo/');

        $leaf
            ->expects($this->any())
            ->method('hasParent')
            ->willReturn(true);

        $leaf->expects($this->any())
            ->method('getParent')
            ->willReturn($parent);

        $leaf->expects($this->any())
            ->method('getPath')
            ->willReturn('/en/foo/bar/');

        return $leaf;
    }
}

