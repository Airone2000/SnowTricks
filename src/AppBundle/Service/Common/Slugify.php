<?php

namespace AppBundle\Service\Common;

class Slugify
{
    private function wdRemoveAccents($str, $charset='utf-8')
    {
        $str = htmlentities($str, ENT_NOQUOTES, $charset);

        $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
        $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
        $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères

        return $str;
    }

    public function exec($str){
        $str = $this->wdRemoveAccents($str);
        $str = preg_replace('~[^\\pL\d]+~u', '-', $str);
        $str = trim($str, '-');
        $str = iconv('utf-8', 'us-ascii//TRANSLIT', $str);
        $str = strtolower($str);
        $str = preg_replace('~[^-\w]+~', '', $str);
        return $str;
    }
}