<?php

namespace Tests\AppBundle\ShowFinder;


use AppBundle\Entity\Category;
use AppBundle\Entity\Show;
use AppBundle\Model\ShowFinderInterface;
use AppBundle\ShowFinder\OMDBShowFinder;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class OMDBShowFinderTest extends TestCase
{

    public function testOMDBReturnsNoShows()
    {
        $params = [
            'endpoint' =>  'http://www.omdbapi.com/',
            'api_key' => '118f043f'
        ];

        $client = $this
                ->getMockBuilder(Client::class)
                ->disableOriginalConstructor()
                ->getMock();

        $results = $this
            ->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();

        $results->method('getBody')->willReturn('{"Response" : "False", "Error" : "Series not found!"}');

        $client->method('request')->willReturn($results);

        $tokenStorage = $this->createMock(TokenStorage::class);

        $omdb = new OMDBShowFinder($client, $params, $tokenStorage);

        $res = $omdb->findByName('soleil');
        $this->assertSame([], $res);
    }

    public function testFindShows() {
        $params = [
            'endpoint' =>  'http://www.omdbapi.com/',
            'api_key' => '118f043f'
        ];

        $client = $this
            ->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();

        $results = $this
            ->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();

        $results->method('getBody')->willReturn('{"Response" : "False", "Error" : "Series not found!"}');

        $client->method('request')->willReturn($results);

        $tokenStorage = $this->createMock(TokenStorage::class);

        $omdb = new OMDBShowFinder($client, $params, $tokenStorage);

        $res = $omdb->findByName('soleil');
        $this->assertSame([], $res);
    }
}