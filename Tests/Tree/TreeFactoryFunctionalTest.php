<?php

namespace Truelab\KottiFrontendBundle\Tests\Tree;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Truelab\KottiFrontendBundle\Tree\TreeFactoryInterface;
use Truelab\KottiModelBundle\Repository\RepositoryInterface;

/**
 * Class TreeFactoryFunctionalTest
 * @package Truelab\KottiFrontendBundle\Tests\Tree
 * @group functional
 */
class TreeFactoryFunctionalTest extends WebTestCase
{
    /**
     * @var TreeFactoryInterface
     */
    private $treeFactory;

    /**
     * @var RepositoryInterface
     */
    private $repository;

    public function setUp()
    {
        $client = self::createClient();
        $this->treeFactory = $client->getContainer()->get('truelab_kotti_frontend.tree_factory');
        $this->repository  = $client->getContainer()->get('truelab_kotti_model.alias_repository');
    }

    public function testServiceExists()
    {
        $this->assertInstanceOf('\Truelab\KottiFrontendBundle\Tree\TreeFactoryInterface', $this->treeFactory);
    }
}
