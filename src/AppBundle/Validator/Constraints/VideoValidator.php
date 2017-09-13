<?php

namespace AppBundle\Validator\Constraints;

use AppBundle\Service\Trick\VideoUri;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */

class VideoValidator extends ConstraintValidator
{
    private $videoUriService;

    public function __construct(VideoUri $videoService)
    {
        $this->videoUriService = $videoService;
    }

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        $value = $this->videoUriService->get(trim($value));
        if($value) return;

        $this->context->buildViolation($constraint->message)->addViolation();
    }
}