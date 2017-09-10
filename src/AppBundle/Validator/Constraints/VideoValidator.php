<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\ConstraintValidatorInterface;

/**
 * @Annotation
 */

class VideoValidator extends ConstraintValidator
{
    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        $value = trim($value);

        # Récupérer une url embed à partir de l'url
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
                $videoId = preg_match('#\/video\/(.*)$#', $value['path'], $match);
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
                # Sauvegarder l'url dans une propriété ?
                if ($property = $constraint->urlProperty)
                {

                    # Particularité dailymotion
                    $value = preg_replace('#^\/\/#', 'http://', $value);

                    $entity = $this->context->getObject();
                    $propertyAccessor = PropertyAccess::createPropertyAccessor();
                    $propertyAccessor->setValue($entity, $property, $value);
                }

                return;
            }
        }

        $this->context->buildViolation($constraint->message)->addViolation();
    }
}