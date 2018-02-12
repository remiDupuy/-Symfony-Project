<?php

namespace AppBundle\ShowFinder;


use AppBundle\Model\ShowFinderInterface;
use GuzzleHttp\Client;

class OMDBShowFinder implements ShowFinderInterface
{
    private $params_omdb;
    private $client;

    public function __construct($params_omdb)
    {
        $this->params_omdb= $params_omdb;
        $this->client = new Client([
            'base_uri' => $this->params_omdb['endpoint'],
        ]);
    }

    public function findByName($query)
    {
        $res = $this->client->request('GET', '', [
            'query' => [
                'apikey' => $this->params_omdb['api_key'],
                's' => $query,
                'type' => 'series',
                'plot' => 'full'
            ]
        ]);

        $series = \GuzzleHttp\json_decode($res->getBody(), 1)['Search'];

        foreach ($series as &$serie) {
            $serie = $this->getShowInfos($serie['imdbID']);
        }

        dump($series);
        die;

        return ;
    }

    public function getName()
    {
        return 'OMDBShow';
    }

    private function getShowInfos($id_omdb)
    {
        $res = $this->client->request('POST', '', [
            'query' => [
                'apikey' => $this->params_omdb['api_key'],
                'i' => $id_omdb,
                'type' => 'series'
            ]
        ]);

        return \GuzzleHttp\json_decode($res->getBody(), 1);
    }
}