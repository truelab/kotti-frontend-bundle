<?php

namespace Truelab\KottiFrontendBundle\Tests\Pagerfanta\Adapter;
use Truelab\KottiFrontendBundle\Pagerfanta\Adapter\CallableAdapter;
use Pagerfanta\Pagerfanta;

/**
 * Class CallableAdapterTest
 * @package Truelab\KottiFrontendBundle\Tests\Pagerfanta\Adapter
 */
class CallableAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function testAdapter()
    {
        $allResults = [
            'a','b','c','d','e','f','g','h','i','l','m','n','o','p','q','r','s','t','u','v','z'
        ];

        $adapter = new CallableAdapter(function () use ($allResults) {
            return count($allResults);
        }, function ($offset, $length) use($allResults) {
            return array_slice($allResults, $offset, $length);
        });

        $pagerfanta = new Pagerfanta($adapter);

        $this->assertEquals(21, $pagerfanta->count());

        $pagerfanta->setMaxPerPage(5);
        $this->assertTrue($pagerfanta->haveToPaginate());

        $this->assertEquals($pagerfanta->getCurrentPageResults(),['a','b','c','d','e']);
        $this->assertTrue($pagerfanta->hasNextPage());


        $pagerfanta->setCurrentPage(2);
        $this->assertEquals($pagerfanta->getCurrentPageResults(),['f','g','h','i','l']);
        $this->assertTrue($pagerfanta->hasNextPage());

        $pagerfanta->setCurrentPage(5);
        $this->assertEquals($pagerfanta->getCurrentPageResults(),['z']);
        $this->assertFalse($pagerfanta->hasNextPage());
    }
}
