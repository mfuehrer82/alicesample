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
        $this->client->request(
            'POST',
            '/api/tips.json',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"tip": {"day":"3"}}'
        );

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('23', strpos($this->client->getResponse()->getContent(), 'Validation Failed'));
    }

    /**
     * @return void
     */
    public function testJsonPostTipAction()
    {
        $content = '{"day":3,"month":12,"description":"some tip"}';

        $this->client->request(
            'POST',
            '/api/tips.json',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            sprintf('{"tip": %s}', $content)
        );

        $repsonse = $this->client->getResponse();

        $this->assertEquals(200, $repsonse->getStatusCode());
        $this->assertEquals($content, $repsonse->getContent());

        $this->client->request('GET', $repsonse->headers->get('Location'));
        $this->assertEquals($content, $repsonse->getContent());
    }

    /**
     * @return Response
     */
    public function testJsonNewTipAction()
    {
        $this->client->request(
            'GET',
            '/api/tips/new'
        );

        $response = $this->client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('{"children":{"day":{},"month":{},"description":{}}}', $response->getContent());

        return $response;
    }

    /**
     * @return Response
     */
    public function testJsonDeleteTipAction()
    {
        $this->client->request(
            'Delete',
            '/api/tips/3'
        );

        $response = $this->client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());

        return $response;
    }

    /**
     * @return Response
     */
    public function testJsonGetTipAction()
    {
        $content = '{"day":10,"month":10,"description":"Ratione eaque possimus quia optio."}';

        $this->client->request(
            'GET',
            '/api/tips/1.json'
        );

        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($content, $response->getContent());

        return $response;
    }

    /**
     * @return void
     */
    public function testCGetAction()
    {
        $this->client->request(
            'GET',
            '/api/tips?page=2'
        );

        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('2', strpos($this->client->getResponse()->getContent(), '\"page\":2,'));
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

        $this->client->request(
            'POST',
            '/api/tips/1/update.json',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            sprintf('{"tip": %s}', $content)
        );

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals($content, $this->client->getResponse()->getContent());
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
