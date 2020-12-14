<?php

namespace Umbrella\CoreBundle\Component\UmbrellaFile\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Umbrella\CoreBundle\Component\UmbrellaFile\UmbrellaFileHelper;

/**
 * Class UmbrellaFileExtension
 */
class UmbrellaFileExtension extends AbstractExtension
{
    /**
     * @var UmbrellaFileHelper
     */
    private $helper;

    /**
     * UmbrellaFileExtension constructor.
     *
     * @param UmbrellaFileHelper $helper
     */
    public function __construct(UmbrellaFileHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('file_url', [$this->helper, 'getUrl']),
            new TwigFunction('image_url', [$this->helper, 'getImageUrl'])
        ];
    }
}
