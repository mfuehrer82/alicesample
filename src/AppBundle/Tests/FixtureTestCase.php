<?php

namespace AppBundle\Tests;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;

/**
 * Class FixtureTestCase
 *
 * @package AppBundle\Tests
 */
class FixtureTestCase extends WebTestCase
{
    /**
     * @var Application
     */
    private static $application;

    /**
     * @var Client
     */
    protected static $client;

    /**
     * @var EntityManager
     */
    protected static $entityManager;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->loadFixtures();
    }

    /**
     * @return void
     */
    public static function setUpBeforeClass()
    {
        self::getApplication();
        self::createSchema();

    }

    /**
     * @return void
     */
    public static function  tearDownAfterClass()
    {
        self::runCommand('doctrine:database:drop --force');
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @return void
     */
    protected function loadFixtures()
    {
        \Nelmio\Alice\Fixtures::load($this->getDataFixtures(), self::$entityManager);
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
    private static function createSchema()
    {
        self::runCommand('doctrine:database:create');
        self::runCommand('doctrine:schema:update --force');
    }

    /**
     * @param string $command
     *
     * @return int|mixed
     */
    private static function runCommand($command)
    {
        $command = sprintf('%s --quiet', $command);

        return self::getApplication()->run(new StringInput($command));
    }

    /**
     * @return Application
     */
    private static function getApplication()
    {
        if (null === self::$application) {
            self::$client = static::createClient();
            self::$entityManager = self::$client->getContainer()->get('doctrine.orm.default_entity_manager');
            self::$application = new Application(self::$client->getKernel());
            self::$application->setAutoExit(false);
        }

        return self::$application;
    }
}
