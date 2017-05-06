<?php


namespace tests\AppBundle\Controller\Api;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class ObservationControllerTest extends TestCase
{
    public function testPOST()
    {
        $client = new \GuzzleHttp\Client([
            'base_url' => 'http://localhost:8000',
            'defaults' => [
                'exceptions' => false
            ]
        ]);

        $data = array(
            'location' => 'Dębieńsko',
            'state' => 'Śląskie',
            'coordinates' => 'dada',
            'images' => 'lala',
            'description' => 'Opis'
        );

        $response = $client->post('/api/observation', [
            'body' => json_encode($data)
        ]);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertTrue($response->hasHeader('Location'));
        $finishedData = json_decode($response->getBody(true), true);
        $this->assertArrayHasKey('userName', $finishedData);
    }
}