<?php

namespace AppBundle\Service\Trick;

class VideoUri
{
    /**
     * @param $value
     * @return mixed|null|string
     */
    public function get($value){

        if( filter_var($value, FILTER_VALIDATE_URL) )
        {
            $value = parse_url($value);

            if( ($value['host'] ?? null) === 'www.youtube.com' && ($value['query'] ?? null) !== null )
            {
                $videoId = preg_match('#v=(.*)$#', $value['query'], $match);
                $videoId = $match[1] ?? null;

                if($videoId !== null)
                {
                    $value = "https://www.youtube.com/embed/$videoId";
                }
            }
            elseif( ($value['host'] ?? null) === 'www.dailymotion.com' && ($value['path'] ?? null) !== null )
            {
                preg_match('#\/video\/(.*)$#', $value['path'], $match);
                $videoId = $match[1] ?? null;

                if($videoId !== null)
                {
                    $value = "//www.dailymotion.com/embed/video/$videoId";
                }

            }
            else
            {
                $value = null;
            }

        }
        else
        {
            preg_match('/src="([^"]+)"/', $value, $match);
            $value = $match[1] ?? null;
        }


        # Vérifier globalement qu'on a bien une URL
        if(is_string($value) && $value !== null)
        {
            if (preg_match('#^https:\/\/www.youtube.com/embed/.*$#', $value) || preg_match('#^\/\/www.dailymotion.com/embed/video/.*$#', $value))
            {
                $value = preg_replace('#^\/\/#', 'http://', $value);
                return $value;
            }
        }

        return null;
    }

}