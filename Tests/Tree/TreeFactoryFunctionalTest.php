<?php

namespace Truelab\KottiFrontendBundle\Tests\Tree;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class TreeFactoryFunctionalTest
 * @package Truelab\KottiFrontendBundle\Tests\Tree
 * @group functional
 */
class TreeFactoryFunctionalTest extends WebTestCase
{
    private $treeFactory;

    public function setUp()
    {
        $client = self::createClient();
        $this->treeFactory = $client->getContainer()->get('truelab_kotti_frontend.tree_factory');
    }

    public function testServiceExists()
    {
        $this->assertInstanceOf('\Truelab\KottiFrontendBundle\Tree\TreeFactoryInterface', $this->treeFactory);
    }
}
