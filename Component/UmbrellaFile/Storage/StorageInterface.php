<?php

namespace Umbrella\CoreBundle\Component\UmbrellaFile\Storage;

use Umbrella\CoreBundle\Entity\UmbrellaFile;

/**
 * Interface StorageInterface
 */
interface StorageInterface
{
    /**
     * @param UmbrellaFile $umbrellaFile
     */
    public function upload(UmbrellaFile $umbrellaFile): void;

    /**
     * Remove file
     */
    public function remove(UmbrellaFile $umbrellaFile): void;

    /**
     * Get the path for a file
     */
    public function getPath(UmbrellaFile $umbrellaFile, bool $relative = false): string;

    /**
     * Get the path for a file
     */
    public function getUrl(UmbrellaFile $umbrellaFile, bool $relative = false): string;
}
