<?php

namespace Umbrella\CoreBundle\Component\DateTime;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Class DateTimeExtension
 */
class DateTimeExtension extends AbstractExtension
{
    /**
     * @var DateTimeHelper
     */
    private $helper;

    /**
     * TimeExtension constructor.
     *
     * @param DateTimeHelper $helper
     */
    public function __construct(DateTimeHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * Returns a list of global functions to add to the existing list.
     *
     * @return array An array of global functions
     */
    public function getFunctions()
    {
        return [
            new TwigFunction(
                'time_diff',
                [$this, 'diff'],
                ['is_safe' => ['html']]
            ),
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter(
                'ago',
                [$this, 'diff'],
                ['is_safe' => ['html']]
            ),
        ];
    }

    public function diff($since, $to = null)
    {
        return $this->helper->diff($since, $to);
    }
}
