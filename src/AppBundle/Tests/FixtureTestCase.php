<?php

namespace AppBundle\Tests;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class FixtureTestCase
 *
 * @package AppBundle\Tests
 */
class FixtureTestCase extends BaseWebTestCase
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->container = $this->client->getContainer();
        $this->em = $this->container->get('doctrine.orm.default_entity_manager');
        $this->createSchema();
        $this->loadFixtures();
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @return void
     */
    protected function loadFixtures()
    {
        \Nelmio\Alice\Fixtures::load($this->getDataFixtures(), $this->em);
    }

    /**
     * @return array
     */
    protected function getDataFixtures()
    {
        return [];
    }

    /**
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    private function createSchema()
    {
        $cmf = $this->em->getMetadataFactory();
        $classes = $cmf->getAllMetadata();

        $schemaTool = new SchemaTool($this->em);
        $schemaTool->createSchema($classes);
    }
}
