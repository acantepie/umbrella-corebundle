<?php

namespace Umbrella\CoreBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Umbrella\CoreBundle\Entity\UmbrellaFile;
use Umbrella\CoreBundle\Services\UmbrellaFileUploader;
use Symfony\Component\Validator\Constraints\FileValidator;

/**
 * Class UmbrellaFileValidator
 */
class UmbrellaFileValidator extends FileValidator
{
    /**
     * @var UmbrellaFileUploader
     */
    private $fileUploader;

    /**
     * UmbrellaFileValidator constructor.
     * @param UmbrellaFileUploader $fileUploader
     */
    public function __construct(UmbrellaFileUploader $fileUploader)
    {
        $this->fileUploader = $fileUploader;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!is_a($value, UmbrellaFile::class)) {
            return;
        }

        /** @var UmbrellaFile $value */

        if ($value->file) {
            parent::validate($value->file, $constraint);
            return;
        }

        $path = $value->getWebPath();
        if (empty($path)) {
            return;
        }

        $value = $this->fileUploader->getAbsolutePath($value);
        parent::validate($value, $constraint);
    }
}
