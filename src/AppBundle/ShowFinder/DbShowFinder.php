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
        $repo_show = $this->em->getRepository(Show::class);
        return $repo_show->findAllByQuery($query);
    }

    public function getName()
    {
        return 'DbShow';
    }
}