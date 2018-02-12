<?php
namespace AppBundle\Model;


/**
 * Interface ShowFinderInterface
 * @package AppBundle\Model
 */
interface ShowFinderInterface
{
    /**
     * Return an array of shows according to the query
     *
     * @param String $query
     * @return array of shows
     */
    public function findByName($query);


    /**
     * Return the name of the implementation of the ShowFinder
     * @return String
     */
    public function getName();
}