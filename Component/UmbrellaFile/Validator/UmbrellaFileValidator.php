<?php

namespace Umbrella\CoreBundle\Component\UmbrellaFile\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\FileValidator;
use Umbrella\CoreBundle\Component\UmbrellaFile\Storage\StorageInterface;
use Umbrella\CoreBundle\Entity\UmbrellaFile;

/**
 * Class UmbrellaFileValidator
 */
class UmbrellaFileValidator extends FileValidator
{
    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * UmbrellaFileValidator constructor.
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
