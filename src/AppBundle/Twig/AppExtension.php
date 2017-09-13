<?php

namespace AppBundle\Twig;

use Doctrine\ORM\PersistentCollection;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('shuffle', [$this, 'shuffleFilter'])
        ];
    }

    public function shuffleFilter(PersistentCollection $array)
    {
        $array = $array->toArray();
        shuffle($array);
        return $array;
    }
}