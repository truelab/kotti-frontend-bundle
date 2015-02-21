<?php

namespace Truelab\KottiFrontendBundle\Tests\Controller;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class ContextControllerTest
 * @package Truelab\KottiFrontendBundle\Tests\Controller
 * @group functional
 */
class ContextControllerTest extends WebTestCase
{
    /**
     * @dataProvider providePaths
     *
     * @param $path
     */
    public function testGetActionIsSuccessful($path)
    {
        $client = self::createClient();

        $client->followRedirects();
        $client->request('GET', $path);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function providePaths()
    {
        return array(
            array('/'),
            array('/about'),
            array('/about/foo'),
            array('/about/foo/bar')
            // ...
        );
    }
}
