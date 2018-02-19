<?php

namespace AppBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return array(
            new TwigFunction('joinColumn', array($this, 'joinColumn')),
        );
    }

    public function joinColumn($array, $column, $delimiter = ',')
    {
        if(!is_array($array))
            return;

        $join_array = [];
        foreach ($array as $item) {
            $join_array[] = $item->{'get'.ucfirst($column)}();
        }

        return implode(', ', $join_array);
    }

    public function getName()
    {
        return 'joinColumn';
    }
}