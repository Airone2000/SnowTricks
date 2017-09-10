<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */

class Video extends Constraint
{

    public $message = "Veuillez saisir une URL/Code Youtube/Dailymotion valide";
    public $urlProperty;


    public function __construct($options = null)
    {
        $this->urlProperty = $options['urlProperty'] ?? null;

        parent::__construct($options);
    }
}