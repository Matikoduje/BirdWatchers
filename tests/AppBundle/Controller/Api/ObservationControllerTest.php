<?php

include 'ApiTestCase.php';

class ObservationControllerTest extends \tests\AppBundle\Controller\Api\ApiTestCase
{
    public function testPOST()
    {
        $data = array(
            'location' => 'Dębieńsko',
            'state' => 'Śląskie',
            'coordinates' => 'dada',
            'images' => 'lala',
            'description' => 'Opis'
        );

        $response = $this->client->post('/api/observation', [
            'body' => json_encode($data)
        ]);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertTrue($response->hasHeader('Location'));
        //$this->assertEquals('/api/observation/ObjectOrienter', $response->getHeader('Location'));
        $finishedData = json_decode($response->getBody(true), true);
        $this->assertArrayHasKey('userName', $finishedData);
        //$this->assertEquals('ObjectOrienter', $finishedData['id']);
    }
}