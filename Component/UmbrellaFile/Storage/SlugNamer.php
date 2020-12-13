<?php

namespace Umbrella\CoreBundle\Component\UmbrellaFile\Storage;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\String\Slugger\SluggerInterface;
use Umbrella\CoreBundle\Entity\UmbrellaFile;
use Umbrella\CoreBundle\Utils\FileUtils;

/**
 * Class SlugNamer
 */
class SlugNamer
{
    /**
     * @var SluggerInterface
     */
    private $slugger;

    /**
     * SlugNamer constructor.
     * @param SluggerInterface $slugger
     */
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    /**
     * Give the storage name to uploaded file
     *
     * @param UmbrellaFile $file
     * @return string
     */
    public function name(string $dirPath, string $originalName): string
    {
        $extension = \strtolower(\pathinfo($originalName, PATHINFO_EXTENSION));
        $dotExtension = '' !== $extension ? sprintf('.%s', $extension) : '';

        $basename = \substr(\pathinfo($originalName, PATHINFO_FILENAME), 0, 240);
        $basename = \strtolower($this->slugger->slug($basename, '-'));

        $slug = \sprintf('%s%s', $basename, $dotExtension);

        $fs = new Filesystem();
        // check if there another object with same slug
        $num = 0;
        while (true) {

            if (false === $fs->exists(FileUtils::resolvePath($dirPath, $slug))) {
                return $slug;
            }

            $slug = \sprintf('%s-%d%s', $basename, ++$num, $dotExtension);
        }
    }
}
