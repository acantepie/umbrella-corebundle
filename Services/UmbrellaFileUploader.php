<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 06/06/17
 * Time: 23:16
 */

namespace Umbrella\CoreBundle\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\KernelInterface;
use Umbrella\CoreBundle\Entity\UmbrellaFile;

/**
 * Class UmbrellaFileUploader
 */
class UmbrellaFileUploader
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var string
     */
    private $assetPath;

    /**
     * @var string
     */
    private $absoluteWebPath;

    /**
     * UmbrellaFileUploader constructor.
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /* Call by Bundle configurator */

    public function loadConfig(array $config)
    {
        $this->absoluteWebPath = sprintf('%s/../%s/', $this->kernel->getRootDir(),  trim($config['web_path'], '/'));
        $this->assetPath = sprintf('%s/', trim($config['asset_path'], '/'));
    }

    /**
     * @return string
     */
    public function getAbsolutePath(UmbrellaFile $file = null)
    {
        return $file 
            ? $this->absoluteWebPath . $file->getWebPath()
            : $this->absoluteWebPath . $this->assetPath;
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function upload(UploadedFile $file)
    {
        $this->createAssetDirectory();

        $filename = $this->generateFilename();
        if (!empty($file->guessClientExtension())) {
            $filename .= '.' . $file->guessClientExtension();
        }
        $file->move($this->getAbsolutePath(), $filename);

        return '/' . $this->assetPath . $filename;
    }

    /**
     * Create Umbrella file from UploadedFile
     * If upload set to true => process upload else upload will be processed on postPersist
     *
     * @param UploadedFile $file
     * @param bool $upload
     * @return UmbrellaFile
     */
    public function createUmbrellaFile(UploadedFile $file, $upload = false)
    {
        $umbrellaFile = new UmbrellaFile();
        $umbrellaFile->name = $file->getClientOriginalName();
        $umbrellaFile->md5 = md5_file($file->getRealPath());
        $umbrellaFile->mimeType = $file->getMimeType();
        $umbrellaFile->size = $file->getSize();

        if ($upload) {
            $umbrellaFile->path = $this->upload($file);
        } else {
            $umbrellaFile->file = $file;
        }
        return $umbrellaFile;
    }

    /**
     * Create Umbrella file from path
     *
     * @param UploadedFile $file
     * @param bool $upload
     * @return UmbrellaFile
     */
    public function createUmbrellaFileFromPath($path, $filename = null, $move = true)
    {
        $umbrellaFile = new UmbrellaFile();
        $umbrellaFile->name = $filename ? $filename : pathinfo($path, PATHINFO_BASENAME);
        $umbrellaFile->md5 = md5_file($path);
        $umbrellaFile->mimeType = mime_content_type($path);
        $umbrellaFile->size = filesize($path);

        if ($move) {
            $this->createAssetDirectory();
            $filename = $this->generateFilename() . '.' . pathinfo($path, PATHINFO_EXTENSION);
            @copy($path, $this->getAbsolutePath() . $filename);
            $umbrellaFile->path = '/' . $this->assetPath . $filename;
        }
        return $umbrellaFile;
    }

    /**
     * Create asset direcory
     */
    public function createAssetDirectory()
    {
        if (!is_dir($this->getAbsolutePath())) {
            @mkdir($this->getAbsolutePath(), 0777, true);
        }
    }

    /**
     * @return string
     */
    private function generateFilename()
    {
        return md5(uniqid('', true));
    }
}