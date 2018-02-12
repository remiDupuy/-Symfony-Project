<?php

namespace AppBundle\ShowFinder;


use AppBundle\Model\ShowFinderInterface;

class OMDBShowFinder implements ShowFinderInterface
{

    public function findByName($query)
    {
        // TODO: Implement findByName() method.
    }

    public function getName()
    {
        return 'OMDBShow';
    }
}