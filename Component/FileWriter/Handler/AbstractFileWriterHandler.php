<?php

namespace Umbrella\CoreBundle\Component\FileWriter\Handler;

use Symfony\Component\Filesystem\Filesystem;
use Umbrella\CoreBundle\Entity\UmbrellaFileWriterConfig;

/**
 * Class AbstractFileWriterHandler
 */
abstract class AbstractFileWriterHandler
{
    /**
     * @var string
     */
    protected $outputDirPath;

    /**
     * @var string
     */
    protected $outputFileName;

    /**
     * @param UmbrellaFileWriterConfig $config
     */
    protected $config;

    /**
     * Call on service build
     *
     * @param $outputDirPath
     */
    final private function __initializeService($outputDirPath)
    {
        $this->outputDirPath = $outputDirPath;
    }

    /**
     * Initialize File writer (call before write)
     *
     * @param UmbrellaFileWriterConfig $config
     */
    public function initialize(UmbrellaFileWriterConfig $config)
    {
        $this->config = $config;
        $this->outputFileName = md5(uniqid(time(), true));

        $fs = new Filesystem();
        if (!$fs->exists($this->outpoutDirPath)) {
            $fs->mkdir($this->outpoutDirPath);
        }
    }

    /**
     * @param UmbrellaFileWriterConfig $config
     */
    public function execute()
    {
    }

    // helper

    /**
     * @return string
     */
    protected function getOutputFilePath()
    {
        return sprintf('%s%s', $this->outputDirPath, $this->outputFileName);
    }
}
