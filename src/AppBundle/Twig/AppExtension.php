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
            new \Twig_SimpleFilter('markdown', [$this, 'markdown']),
            new \Twig_SimpleFilter('purify', [$this, 'purify'], ['is_safe' => ['html']])
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

    public function purify($text)
    {
        $elements = array(
            'p',
            'br',
            'small',
            'strong', 'b',
            'em', 'i',
            'strike',
            'sub', 'sup',
            'ins', 'del',
            'ol', 'ul', 'li',
            'h1', 'h2', 'h3',
            'dl', 'dd', 'dt',
            'pre', 'code', 'samp', 'kbd',
            'q', 'blockquote', 'abbr', 'cite',
            'table', 'thead', 'tbody', 'th', 'tr', 'td',
            'a[href|target|rel|id]',
            'img[src|title|alt|width|height|style]'
        );

        $config = \HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', implode(',', $elements));

        $purifier = new \HTMLPurifier($config);
        return $purifier->purify($text);
    }
}