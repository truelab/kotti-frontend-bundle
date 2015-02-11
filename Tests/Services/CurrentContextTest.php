<?php

namespace Truelab\KottiFrontendBundle\Tests\Services;
use Truelab\KottiFrontendBundle\Services\CurrentContext;
use Truelab\KottiModelBundle\Model\Node;
use Truelab\KottiMultilanguageBundle\Model\LanguageRoot;

/**
 * Class CurrentContextTest
 * @package Truelab\KottiFrontendBundle\Tests\Services
 * @group unit
 */
class CurrentContextTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CurrentContext
     */
    protected $currentContext;

    public function testContextLineage()
    {
        $chooser = $this->getMock('Truelab\KottiFrontendBundle\Services\NavigationRootChooserInterface', array('choose'));
        $currentContext = new CurrentContext($chooser);
        $currentContext->set($this->provideNode());

        $lineage = $currentContext->lineage();

        $paths = array_map(function ($node) {
            return $node->getPath();
        }, $lineage);

        $this->assertEquals(['/en/foo/','/en/','/'], $paths);
    }

    public function testNavigationRootIsRoot()
    {
        $chooser = $this->getMock('Truelab\KottiFrontendBundle\Services\NavigationRootChooserInterface', array('choose'));

        $chooser
            ->expects($this->any())
            ->method('choose')
            ->willReturn(false);

        $currentContext = new CurrentContext($chooser);
        $currentContext->set($this->provideNode());

        $this->assertEquals('/', $currentContext->navigationRoot()->getPath());
    }

    public function testNavigationRootIsNotRoot()
    {
        $chooser = $this->getMock('Truelab\KottiFrontendBundle\Services\NavigationRootChooserInterface', array('choose'));

        $chooser
            ->expects($this->any())
            ->method('choose')
            ->willReturnCallback(function($node) {
                return $node->getPath() === '/en/';
            });

        $currentContext = new CurrentContext($chooser);
        $currentContext->set($this->provideNode());

        $this->assertEquals('/en/', $currentContext->navigationRoot()->getPath());
    }

    public function provideNode()
    {
        $class = 'Truelab\KottiModelBundle\Model\NodeInterface';
        $methods = array('hasParent','getParent','getPath','equals','getChildren','hasChildren','setRepository','getId');


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


