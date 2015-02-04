<?php

namespace Truelab\KottiFrontendBundle\Tests\Services;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Truelab\KottiFrontendBundle\Services\NodeManipulatorInterface;
use Truelab\KottiORMBundle\Repository\NodeRepositoryInterface;

/**
 * Class NodeManipulatorFunctionalTest
 * @package Truelab\KottiFrontendBundle\Tests\Services
 */
class NodeManipulatorFunctionalTest extends WebTestCase
{

    /**
     * @var NodeRepositoryInterface
     */
    private $nodeRepository;

    /**
     * @var NodeManipulatorInterface
     */
    private $nodeManipulator;

    public function setUp()
    {
        $client = self::createClient();
        $this->nodeRepository = $client->getContainer()->get('truelab_kotti_orm.node_repository');
        $this->nodeManipulator = $client->getContainer()->get('truelab_kotti_frontend.services.node_manipulator');
    }

    public function testGetParents()
    {
        $parents = $this->nodeManipulator->getParents($this->getNode());
        $expectedSizeOfParents = 3;
        $this->assertEquals($expectedSizeOfParents, sizeof($parents),
            sprintf('I expect that node at path = "%s" has "%s" flat parents', $this->getPath(), $expectedSizeOfParents)
        );

        $this->assertEquals($parents[$expectedSizeOfParents - 1]->getPath(), '/');
    }

    public function testGetParentsWithDeep()
    {
        $expectedSizeOfParents = 2;
        $parents = $this->nodeManipulator->getParents($this->getNode(), $expectedSizeOfParents);
        $this->assertEquals($expectedSizeOfParents, sizeof($parents),
            sprintf('I expect that node at path = "%s" has "%s" flat parents', $this->getPath(), $expectedSizeOfParents)
        );
        $this->assertEquals($parents[$expectedSizeOfParents - 1]->getPath(), '/en/');
    }

    public function testGetParentsReversed()
    {
        $expectedSizeOfParents = 3;
        $parents = $this->nodeManipulator->getParentsReversed($this->getNode());
        $this->assertEquals($expectedSizeOfParents, sizeof($parents),
            sprintf('I expect that node at path = "%s" has "%s" flat parents', $this->getPath(), $expectedSizeOfParents)
        );

        $this->assertEquals($parents[$expectedSizeOfParents - 1]->getPath(), '/en/mip/');
    }

    protected function getNode()
    {
        return $this->nodeRepository->getOneWherePath($this->getPath());
    }

    protected function getPath()
    {
        return '/en/mip/international-network/';
    }
}
