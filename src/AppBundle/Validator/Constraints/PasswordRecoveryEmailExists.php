<?php

namespace AppBundle\Validator\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */

class PasswordRecoveryEmailExists extends Constraint
{
    public $msg = "Cette adresse email n'existe pas.";
}