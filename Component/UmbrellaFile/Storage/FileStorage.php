<?php

namespace Umbrella\CoreBundle\Component\UmbrellaFile\Storage;

use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Umbrella\CoreBundle\Entity\UmbrellaFile;
use Umbrella\CoreBundle\Utils\FileUtils;

/**
 * Class FileStorage
 */
class FileStorage implements StorageInterface
{
    /**
     * @var SlugNamer
     */
    private $namer;

    private string $uploadDir;
    private string $uriPrefix;

    /**
     * FileStorage constructor.
     *
     * @param SlugNamer $namer
     * @param string $uploadDir
     */
    public function __construct(SlugNamer $namer, string $uploadDir, string $uriPrefix)
    {
        $this->namer = $namer;
        $this->uploadDir = rtrim($uploadDir, '/');
        $this->uriPrefix = rtrim($uriPrefix, '/');
    }

    /**
     * @inheritDoc
     */
    public function upload(UmbrellaFile $umbrellaFile) : void
    {
        $dirPath = FileUtils::resolvePath($this->uploadDir, $umbrellaFile->_filePath);

        $file = $umbrellaFile->_file;

        if (null === $file || !$file instanceof UploadedFile) {
            throw new \InvalidArgumentException('Missing or invalid uploaded file');
        }

        // Fix extension if missing on originalName
        $originalName = $file->getClientOriginalName();
        $extension = \pathinfo($originalName, PATHINFO_EXTENSION);
        $fileName = \pathinfo($originalName, PATHINFO_FILENAME);

        if (empty($extension)) {
            $extension = $file->getExtension();
        }

        $fileName = '' !== $extension ? sprintf('%s.%s', $fileName, $extension) : $fileName;

        // get a prettyName using SlugNamer
        $fileName = $this->namer->name($dirPath, $fileName);

        // metadata
        $umbrellaFile->name = FileUtils::resolvePath($umbrellaFile->_filePath, $fileName);
        $umbrellaFile->size = $file->getSize();

        // move
        $file->move($dirPath, $fileName);
    }

    /**
     * @inheritDoc
     */
    public function remove(UmbrellaFile $umbrellaFile) : void
    {
        $path = $this->getPath($umbrellaFile);

        $fs = new Filesystem();
        try {
            $fs->remove($path);
        } catch (IOException $e) {
            // ignore errors
        }
    }

    /**
     * @inheritDoc
     */
    public function getPath(UmbrellaFile $umbrellaFile, bool $relative = false): string
    {
        return $relative
            ? $umbrellaFile->name
            : FileUtils::resolvePath($this->uploadDir, $umbrellaFile->name);
    }

    /**
     * @inheritDoc
     */
    public function getUrl(UmbrellaFile $umbrellaFile, bool $relative = false): string
    {
        if (false === $relative) {
            throw new \LogicException('Generating absolute url using FileStorage isn\'t implemented');
        }

        return FileUtils::resolvePath($this->uriPrefix, $umbrellaFile->name);
    }
}