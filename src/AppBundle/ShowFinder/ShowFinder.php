<?php
namespace AppBundle\ShowFinder;


use AppBundle\Model\ShowFinderInterface;

/**
 * Class ShowFinder
 * @package AppBundle\ShowFinder
 */
class ShowFinder
{
    /**
     * @var array of ShowFinderInterface
     */
    private $finders = [];


    /**
     * @return mixed
     */
    public function getFinders()
    {
        return $this->finders;
    }

    /**
     * @param mixed $finders
     */
    public function setFinders($finders)
    {
        $this->finders = $finders;
    }


    /**
     * @param $finder
     */
    public function addFinder(ShowFinderInterface $finder) {
        $this->finders[] = $finder;
    }

    /**
     * @param $query
     */
    public function searchByName($query) {
        $results = [];
        foreach ($this->getFinders() as $finder) {
            $results[$finder->getName()] = $finder->findByName($query);
        }

        dump($results); die;
        return $results;
    }

}