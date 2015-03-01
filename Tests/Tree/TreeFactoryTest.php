<?php

namespace Truelab\KottiFrontendBundle\Tests\Tree;
use Truelab\KottiFrontendBundle\Tree\TreeFactory;
use Truelab\KottiModelBundle\Model\NodeInterface;

/**
 * Class TreeFactoryTest
 * @package Truelab\KottiFrontendBundle\Tests\Tree
 * @group unit
 */
class TreeFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetTree()
    {
        $rootMock = $this->getRootNodeMock();
        $rootMockChildren = $rootMock->getChildren();

        $treeRoot = TreeFactory::getTree($rootMock, function (NodeInterface $nodeMock) {
            return $nodeMock->getChildren();
        }, 4);

        $this->assertTrue($treeRoot->isRoot(), 'I expect that current tree node is root');
        $this->assertCount(count($rootMockChildren), $treeRoot->getChildren());
        $this->assertEquals($rootMockChildren[0]->getPath(), $treeRoot->getChildren()[0]->getValue()->getPath());

        $bNodeMockChildren = $rootMockChildren[1]->getChildren(); // d, e;

        $bTreeChildren = $treeRoot->getChildren()[1]->getChildren();
        foreach($bNodeMockChildren as $i => $nodeMockChild) {
            $this->assertEquals($nodeMockChild->getPath(), $bTreeChildren[$i]->getValue()->getPath());
        }

        $lTreeLeaf = $bTreeChildren[0]/* d */->getChildren()[1]/* g */->getChildren()[1]/* l */;
        $this->assertEquals('/b/d/g/l/', $lTreeLeaf->getValue()->getPath());
    }

    public function testGetTreeMaxDepth0()
    {
        $rootMock = $this->getRootNodeMock();

        $treeRoot = TreeFactory::getTree($rootMock, function (NodeInterface $nodeMock) {
            return $nodeMock->getChildren();
        }, 0);

        $this->assertTrue($treeRoot->isRoot(), 'I expect that current tree node is root');
        $this->assertCount(0, $treeRoot->getChildren());
        $this->assertEquals($rootMock->getPath(), $treeRoot->getValue()->getPath());
    }

    public function testGetTreeMaxDepth1()
    {
        $rootMock = $this->getRootNodeMock();
        $rootMockChildren = $rootMock->getChildren();

        $treeRoot = TreeFactory::getTree($rootMock, function (NodeInterface $nodeMock) {
            return $nodeMock->getChildren();
        }, 1);

        $this->assertTrue($treeRoot->isRoot(), 'I expect that current tree node is root');
        $this->assertCount(count($rootMockChildren), $treeRoot->getChildren());
        $this->assertEquals($rootMockChildren[0]->getPath(), $treeRoot->getChildren()[0]->getValue()->getPath());

    }

    /**
     * @expectedException \Truelab\KottiFrontendBundle\Tree\Exception\GetChildrenCallbackException
     */
    public function testGetTreeThrowAnExceptionIfCallbackDoentReturnAnArray()
    {
        $rootMock = $this->getRootNodeMock();

        TreeFactory::getTree($rootMock, function () {
            return '';
        }, 4);
    }

    /**
     * @expectedException \Truelab\KottiFrontendBundle\Tree\Exception\GetChildrenCallbackException
     */
    public function testGetTreeThrowAnExceptionIfCallbackDoentReturnAnArray2()
    {
        $rootMock = $this->getRootNodeMock();

        TreeFactory::getTree($rootMock, function () {
            return null;
        }, 4);
    }

    /**
     * @expectedException \Truelab\KottiFrontendBundle\Tree\Exception\GetChildrenCallbackException
     */
    public function testGetTreeThrowAnExceptionIfCallbackDoentReturnAnArray3()
    {
        $rootMock = $this->getRootNodeMock();

        TreeFactory::getTree($rootMock, function () {

        }, 4);
    }


    /**
     *
     *
     *         root
     *       /  |  \
     *      a   b  c
     *        /  |
     *       d   e
     *    /  | \
     *   f   g  h
     *      /|\
     *     i l m
     *
     */
    public function getRootNodeMock()
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

        // M
        $mNode = $this->getMock($class, $methods);
        $mNode
            ->expects($this->any())
            ->method('getPath')
            ->willReturn('/b/d/g/m/');

        $mNode
            ->expects($this->any())
            ->method('getChildren')
            ->willReturn([]);

        // L
        $lNode = $this->getMock($class, $methods);
        $lNode
            ->expects($this->any())
            ->method('getPath')
            ->willReturn('/b/d/g/l/');

        $lNode
            ->expects($this->any())
            ->method('getChildren')
            ->willReturn([]);

        // I
        $iNode = $this->getMock($class, $methods);
        $iNode
            ->expects($this->any())
            ->method('getPath')
            ->willReturn('/b/d/g/i/');

        $iNode
            ->expects($this->any())
            ->method('getChildren')
            ->willReturn([]);

        // H
        $hNode = $this->getMock($class, $methods);
        $hNode
            ->expects($this->any())
            ->method('getPath')
            ->willReturn('/b/d/h/');

        $hNode
            ->expects($this->any())
            ->method('getChildren')
            ->willReturn([]);

        // G
        $gNode = $this->getMock($class, $methods);
        $gNode
            ->expects($this->any())
            ->method('getPath')
            ->willReturn('/b/d/g/');

        $gNode
            ->expects($this->any())
            ->method('getChildren')
            ->willReturn([
                $iNode,
                $lNode,
                $mNode
            ]);

        // F
        $fNode = $this->getMock($class, $methods);
        $fNode
            ->expects($this->any())
            ->method('getPath')
            ->willReturn('/b/d/f/');

        $fNode
            ->expects($this->any())
            ->method('getChildren')
            ->willReturn([]);

        // E
        $eNode = $this->getMock($class, $methods);
        $eNode
            ->expects($this->any())
            ->method('getPath')
            ->willReturn('/b/e/');

        $eNode
            ->expects($this->any())
            ->method('getChildren')
            ->willReturn([]);

        // D
        $dNode = $this->getMock($class, $methods);
        $dNode
            ->expects($this->any())
            ->method('getPath')
            ->willReturn('/b/d/');

        $dNode
            ->expects($this->any())
            ->method('getChildren')
            ->willReturn([
                $fNode,
                $gNode,
                $hNode
            ]);

        // C
        $cNode = $this->getMock($class, $methods);
        $cNode
            ->expects($this->any())
            ->method('getPath')
            ->willReturn('/c/');

        $cNode
            ->expects($this->any())
            ->method('getChildren')
            ->willReturn([]);

        // B
        $bNode = $this->getMock($class, $methods);
        $bNode
            ->expects($this->any())
            ->method('getPath')
            ->willReturn('/b/');


        $bNode
            ->expects($this->any())
            ->method('getChildren')
            ->willReturn([
                $dNode,
                $eNode
            ]);

        // A
        $aNode = $this->getMock($class, $methods);
        $aNode
            ->expects($this->any())
            ->method('getPath')
            ->willReturn('/a/');

        $aNode
            ->expects($this->any())
            ->method('getChildren')
            ->willReturn([]);

        // ROOT
        $rootNode = $this->getMock($class, $methods);
        $rootNode
            ->expects($this->any())
            ->method('getPath')
            ->willReturn('/');

        $rootNode
            ->expects($this->any())
            ->method('getChildren')
            ->willReturn([
                $aNode,
                $bNode,
                $cNode
            ]);

        return $rootNode;
    }
}
