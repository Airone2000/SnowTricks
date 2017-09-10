<?php

namespace AppBundle\Validator\Constraints;

use AppBundle\Entity\Authentication\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PasswordRecoveryEmailExistsValidator extends ConstraintValidator
{
    public $entityManager;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
    }

    public function validate($value, Constraint $constraint)
    {
        $entity = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $value]);

        if(!$entity)
        {
            $this->context->buildViolation($constraint->msg)->addViolation();
        }


    }
}