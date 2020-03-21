<?php

namespace Umbrella\CoreBundle\Validator\Constraints;

use Umbrella\CoreBundle\Validator\UmbrellaFileValidator;
use Symfony\Component\Validator\Constraints\File;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 * @property int $maxSize
 */
class UmbrellaFileConstraint extends File
{
    /**
     * @return string
     */
    public function validatedBy()
    {
        return UmbrellaFileValidator::class;
    }

}