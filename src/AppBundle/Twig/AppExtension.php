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
            new \Twig_SimpleFilter('video', [$this, 'videoFilter']),
            new \Twig_SimpleFilter('markdown', [$this, 'markdown'])
        ];
    }

    /**
     * Mélange un Array
     *
     * @param \Doctrine\ORM\PersistentCollection $array
     * @return array|\Doctrine\ORM\PersistentCollection
     */
    public function shuffleFilter(PersistentCollection $array)
    {
        $array = $array->toArray();
        shuffle($array);
        return $array;
    }

    /**
     * Extraire une URL valide
     *
     * @param $input
     * @return mixed|null|string
     */
    public function videoFilter($input)
    {
        return $this->serviceVideo->get($input);
    }

    public function markdown($text)
    {
        $mdParser = new \Parsedown();
        return $mdParser->text($text);
    }
}