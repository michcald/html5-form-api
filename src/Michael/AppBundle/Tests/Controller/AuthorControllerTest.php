<?php

namespace Michael\AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthorControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        // CREATE
        $crawler = $client->request(
        	'POST', 
        	'/authors',
        	array(
        		'firstName' => 'Gino',
	        	'lastName'  => 'Corioni'
        	)
        );

        $response = $client->getResponse();

        $this->assertEquals(
            201, 
            $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            $response->headers
        );

        $id = $response->getContent();

        // READ
        $crawler = $client->request(
        	'GET', 
        	'/authors/' . $id
        );

        $response = $client->getResponse();

        $this->assertEquals(
            200, 
            $response->getStatusCode()
        );

        $obj = array(
        	'resource' => array(
        		'id' => (int)$id,
        		'first_name' => 'Gino',
        		'last_name' => 'Corioni',
        		'articles' => array()
        	)
        );

        $this->assertTrue($response->getContent() == json_encode($obj));

        // UPDATE
        $crawler = $client->request(
        	'PUT', 
        	'/authors/' . $id,
        	array(
        		'firstName' => 'Gino2'
        	)
        );

        $response = $client->getResponse();

        $this->assertEquals(
            204, 
            $response->getStatusCode()
        );

        // READ
        $crawler = $client->request(
        	'GET', 
        	'/authors/' . $id
        );

        $response = $client->getResponse();

        $obj = array(
        	'resource' => array(
        		'id' => (int)$id,
        		'first_name' => 'Gino2',
        		'last_name' => 'Corioni',
        		'articles' => array()
        	)
        );

        $this->assertTrue($response->getContent() == json_encode($obj));

        // DELETE
        $crawler = $client->request(
        	'DELETE', 
        	'/authors/' . $id
        );

        $response = $client->getResponse();

        $this->assertEquals(
            204, 
            $response->getStatusCode()
        );

        // READ
        $crawler = $client->request(
        	'GET', 
        	'/authors/' . $id
        );

        $response = $client->getResponse();

        $this->assertEquals(
            404, 
            $response->getStatusCode()
        );

    }
}
