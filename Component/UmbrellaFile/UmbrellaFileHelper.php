<?php


namespace Umbrella\CoreBundle\Component\UmbrellaFile;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Umbrella\CoreBundle\Component\UmbrellaFile\Storage\StorageInterface;
use Umbrella\CoreBundle\Entity\UmbrellaFile;

/**
 * Class UmbrellaFileHelper
 */
class UmbrellaFileHelper
{
    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * @var CacheManager
     */
    private $liipCache;

    /**
     * UmbrellaFileHelper constructor.
     * @param StorageInterface $storage
     * @param CacheManager $liipCache
     */
    public function __construct(StorageInterface $storage, CacheManager $liipCache)
    {
        $this->storage = $storage;
        $this->liipCache = $liipCache;
    }

    public function getPath(UmbrellaFile $umbrellaFile, bool $relative = false) : string
    {
        return $this->storage->getPath($umbrellaFile, $relative);
    }

    public function getUrl(UmbrellaFile $umbrellaFile) : string
    {
        return $path = $this->storage->getUrl($umbrellaFile, true);
    }

    public function getImageUrl(UmbrellaFile $umbrellaFile, $liipFilter = null, array $config = [], $resolver = null) : string
    {
        if (empty($liipFilter)) {
            return $this->getUrl($umbrellaFile);
        }

        return $this->liipCache->getBrowserPath($this->getUrl($umbrellaFile), $liipFilter, $config, $resolver);
    }

}