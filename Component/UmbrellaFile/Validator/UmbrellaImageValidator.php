<?php

namespace Umbrella\CoreBundle\Component\UmbrellaFile\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\ImageValidator;
use Umbrella\CoreBundle\Component\UmbrellaFile\Storage\StorageInterface;
use Umbrella\CoreBundle\Entity\UmbrellaFile;

/**
 * Class UmbrellaImageValidator
 */
class UmbrellaImageValidator extends ImageValidator
{
    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * UmbrellaImageValidator constructor.
     *
     * @param StorageInterface $storage
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!is_a($value, UmbrellaFile::class)) {
            return;
        }

        if ($value->_file) {
            parent::validate($value->_file, $constraint);

            return;
        }

        parent::validate($this->storage->getPath($value), $constraint);
    }
}
