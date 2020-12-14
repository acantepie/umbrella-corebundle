<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 07/06/17
 * Time: 23:02
 */

namespace Umbrella\CoreBundle\Component\UmbrellaFile;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Umbrella\CoreBundle\Component\UmbrellaFile\Storage\StorageInterface;
use Umbrella\CoreBundle\Entity\UmbrellaFile;

/**
 * Class UmbrellaFileListener
 */
class UmbrellaFileListener
{
    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * UmbrellaFileListener constructor.
     *
     * @param StorageInterface $storage
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param UmbrellaFile       $umbrellaFile
     * @param LifecycleEventArgs $event
     */
    public function preRemove(UmbrellaFile $umbrellaFile, LifecycleEventArgs $event): void
    {
        $this->storage->remove($umbrellaFile);
    }

    /**
     * @param UmbrellaFile       $umbrellaFile
     * @param LifecycleEventArgs $event
     */
    public function prePersist(UmbrellaFile $umbrellaFile, LifecycleEventArgs $event): void
    {
        if (null === $umbrellaFile->_file || !$umbrellaFile->_file instanceof UploadedFile) {
            return;
        }

        $this->storage->upload($umbrellaFile);
    }
}
