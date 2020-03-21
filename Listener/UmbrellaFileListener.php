<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 07/06/17
 * Time: 23:02
 */

namespace Umbrella\CoreBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Umbrella\CoreBundle\Entity\UmbrellaFile;
use Umbrella\CoreBundle\Services\UmbrellaFileUploader;

/**
 * Class UmbrellaFileListener
 */
class UmbrellaFileListener
{
    /**
     * @var UmbrellaFileUploader
     */
    private $uploader;

    /**
     * UmbrellaFileListener constructor.
     * @param UmbrellaFileUploader $uploader
     */
    public function __construct(UmbrellaFileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * @param UmbrellaFile $umbrellaFile
     * @param LifecycleEventArgs $event
     */
    public function preRemove(UmbrellaFile $umbrellaFile, LifecycleEventArgs $event)
    {
        @unlink($umbrellaFile->getAbsolutePath());

    }

    /**
     * @param UmbrellaFile $umbrellaFile
     * @param LifecycleEventArgs $event
     */
    public function prePersist(UmbrellaFile $umbrellaFile, LifecycleEventArgs $event)
    {
        if ($umbrellaFile->file instanceof UploadedFile && $umbrellaFile->file->isValid()) {
            $umbrellaFile->path = $this->uploader->upload($umbrellaFile->file);
        }
    }
}