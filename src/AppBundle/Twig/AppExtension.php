<?php

namespace AppBundle\Twig;

use AppBundle\Service\Trick\VideoUri;
use Doctrine\ORM\PersistentCollection;

class AppExtension extends \Twig_Extension
{

    private $serviceVideo;

    public function __construct(VideoUri $videoUri)
    {
        $this->serviceVideo = $videoUri;
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('shuffle', [$this, 'shuffleFilter']),
            new \Twig_SimpleFilter('video', [$this, 'videoFilter'])
        ];
    }

    public function shuffleFilter(PersistentCollection $array)
    {
        $array = $array->toArray();
        shuffle($array);
        return $array;
    }

    public function videoFilter($input)
    {
        return $this->serviceVideo->get($input);
    }
}