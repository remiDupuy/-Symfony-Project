<?php

namespace AppBundle\ShowFinder;


use AppBundle\Entity\Category;
use AppBundle\Entity\Show;
use AppBundle\Model\ShowFinderInterface;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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


        $decoded_data = \GuzzleHttp\json_decode($res->getBody(), 1);
        if(isset($decoded_data['Response']) && $decoded_data['Response'] != 'False') {
            $series = $decoded_data['Search'];
        } else {
            return [];
        }

        $show_array = [];
        foreach ($series as &$serie) {
            $serie = $this->getShowInfos($serie['imdbID']);
            $show = new Show();
            $show->setAuthor($serie['Writer']);
            $show->setName($serie['Title']);
            $show->setPathMainPicture($serie['Poster']);
            $show->setPublishedDate($serie['Released']);
            $show->setIsoCountry($serie['Country']);
            $categories = explode(',', $serie['Genre']);
            $categories = array_map(function ($cat) {
                $cat = trim($cat);
                return (new Category())->setName($cat);
            }, $categories);
            $show->setCategories($categories);
            $show_array[] = $show;
        }

        return $show_array;
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