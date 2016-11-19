<?php

namespace AppBundle\Tests\Functional\Controller;

use AppBundle\Tests\FixtureTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TipControllerTest
 *
 * @package AppBundle\Tests\Functional\Controller
 */
class TipControllerTest extends FixtureTestCase
{
    /**
     * @return void
     */
    public function testJsonPostTipActionFailed()
    {
        self::$client->request(
            'POST',
            '/api/tips.json',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"tip": {"day":"3"}}'
        );

        $this->assertEquals(400, self::$client->getResponse()->getStatusCode());
        $this->assertEquals('23', strpos(self::$client->getResponse()->getContent(), 'Validation Failed'));
    }

    /**
     * @return void
     */
    public function testJsonPostTipAction()
    {
        $content = '{"day":3,"month":12,"description":"some tip"}';

        self::$client->request(
            'POST',
            '/api/tips.json',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            sprintf('{"tip": %s}', $content)
        );

        $repsonse = self::$client->getResponse();

        $this->assertEquals(200, $repsonse->getStatusCode());
        $this->assertEquals($content, $repsonse->getContent());

        self::$client->request('GET', $repsonse->headers->get('Location'));
        $this->assertEquals($content, $repsonse->getContent());
    }

    /**
     * @return Response
     */
    public function testJsonNewTipAction()
    {
        self::$client->request(
            'GET',
            '/api/tips/new'
        );

        $response = self::$client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('{"children":{"day":{},"month":{},"description":{}}}', $response->getContent());

        return $response;
    }

    /**
     * @return Response
     */
    public function testJsonDeleteTipAction()
    {
        self::$client->request(
            'Delete',
            '/api/tips/3'
        );

        $response = self::$client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());

        return $response;
    }

    /**
     * @return Response
     */
    public function testJsonGetTipAction()
    {
        $content = '{"day":18,"month":1,"description":"Recusandae asperiores accusamus nihil."}';

        self::$client->request(
            'GET',
            '/api/tips/1.json'
        );

        $response = self::$client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($content, $response->getContent());

        return $response;
    }

    /**
     * @return void
     */
    public function testCGetAction()
    {
        self::$client->request(
            'GET',
            '/api/tips?page=2'
        );

        $response = self::$client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('2', strpos(self::$client->getResponse()->getContent(), '\"page\":2,'));
    }

    /**
     * @param Response $response
     *
     * @depends testJsonGetTipAction
     * @return void
     */
    public function testJsonUpdateTipAction(Response $response)
    {
        $content = str_replace('"day":10', '"day":5', $response->getContent());

        self::$client->request(
            'POST',
            '/api/tips/1/update.json',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            sprintf('{"tip": %s}', $content)
        );

        $this->assertEquals(200, self::$client->getResponse()->getStatusCode());
        $this->assertEquals($content, self::$client->getResponse()->getContent());
    }

    /**
     * @return array
     */
    protected function getDataFixtures()
    {
        return array_merge(
            parent::getDataFixtures(),
            [
                __DIR__ . '/../../DataFixtures/ORM/tip.yml'
            ]
        );
    }
}
