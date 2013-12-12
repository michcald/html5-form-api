<?php

namespace Michael\AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthorControllerTest extends WebTestCase
{
    private function generateCreates(array $data)
    {
        $client = static::createClient();

        foreach ($data as $item) {
            $crawler = $client->request('POST', '/authors', $item);

            $response = $client->getResponse();

            $this->assertEquals(
                400, 
                $response->getStatusCode(),
                $response->getContent()
            );
        }
    }

    public function testCreate()
    {
        $data = array(
            array(
                'first_name' => 'John'
            ),
            array(
                'first_name' => 'John',
                'last_name' => ''
            ),
            array(
                'first_name' => '',
                'last_name' => 'Doe'
            ),
            array(
                'first_name' => '',
                'last_name' => ''
            ),
            array(
                'any_other_field1' => ''
            ),
            array(
                'any_other_field1' => '',
                'any_other_field2' => ''
            ),
            array(
                'any_other_field1' => 'adas',
                'any_other_field2' => 'asdad'
            ),
            array(
                'any_other_field1' => 'asdads'
            ),
            array(
                'any_other_field1' => 'adas',
                'any_other_field2' => ''
            )
        );
        
        $this->generateCreates($data);

    }

    public function testAll()
    {
        $client = static::createClient();

        // CREATE
        $crawler = $client->request('POST', '/authors',
        	array(
        		'first_name' => 'Gino',
	        	'last_name'  => 'Corioni'
        	)
        );

        $response = $client->getResponse();

        $this->assertEquals(201, $response->getStatusCode(), $response->getContent());
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            $response->headers
        );

        $id = $response->getContent();

        // READ
        $crawler = $client->request('GET', '/authors/' . $id);

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $obj = array(
        	'author' => array(
        		'id' => (int)$id,
        		'first_name' => 'Gino',
        		'last_name' => 'Corioni',
        		//'articles' => array()
        	)
        );

        $this->assertTrue($response->getContent() == json_encode($obj));

        // UPDATE
        $crawler = $client->request('PUT', '/authors/' . $id,
        	array(
        		'first_name' => 'Gino2'
        	)
        );

        $response = $client->getResponse();

        $this->assertEquals(204, $response->getStatusCode());

        // READ
        $crawler = $client->request('GET', '/authors/' . $id);

        $response = $client->getResponse();

        $obj = array(
        	'author' => array(
        		'id' => (int)$id,
        		'first_name' => 'Gino2',
        		'last_name' => 'Corioni',
        		//'articles' => array()
        	)
        );

        $this->assertTrue($response->getContent() == json_encode($obj));

        // DELETE
        $crawler = $client->request('DELETE', '/authors/' . $id);

        $response = $client->getResponse();

        $this->assertEquals(204, $response->getStatusCode());

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
