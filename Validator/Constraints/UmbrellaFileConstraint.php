<?php

namespace Umbrella\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\File;
use Umbrella\CoreBundle\Validator\UmbrellaFileValidator;

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
