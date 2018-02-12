<?php

namespace AppBundle\ShowFinder;


use AppBundle\Entity\Show;
use AppBundle\Model\ShowFinderInterface;

class DbShowFinder implements ShowFinderInterface
{
    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    public function findByName($query)
    {
        return $this->em->getRepository(Show::class);
    }

    public function getName()
    {
        return 'DbShow';
    }
}