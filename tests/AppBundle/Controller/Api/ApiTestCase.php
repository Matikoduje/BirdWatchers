<?php

namespace tests\AppBundle\Controller\Api;

use Exception;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use GuzzleHttp\Subscriber\History;
use GuzzleHttp\Client;
use GuzzleHttp\Message\AbstractMessage;
use GuzzleHttp\Message\ResponseInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\DomCrawler\Crawler;

abstract class ApiTestCase extends KernelTestCase
{
    private static $staticClient;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var History
     */
    private static $history;

    /**
     * @var FormatterHelper
     */
    private $formatterHelper;

    public static function setUpBeforeClass()
    {
        self::$staticClient = new Client([
            'base_url' => 'http://localhost:8000',
            'defaults' => [
                'exceptions' => false
            ]
        ]);

        self::$history = new History();
        self::$staticClient->getEmitter()
            ->attach(self::$history);
        self::bootKernel();
    }

    public function setUp()
    {
        $this->client = self::$staticClient;
        $this->purgeDatabase();
    }

    /**
     * Clean up Kernel usage in this test.
     */
    protected function tearDown()
    {
        // purposefully not calling parent class, which shuts down the kernel
    }

    protected function getService($id)
    {
        return self::$kernel->getContainer()
            ->get($id);
    }

    private function purgeDatabase()
    {
        $purger = new ORMPurger($this->getService('doctrine')->getManager());
        $purger->purge();
    }

}