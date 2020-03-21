<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 28/09/18
 * Time: 17:50
 */

namespace Umbrella\CoreBundle\Component\DateTime;

/**
 * Class DateTimeHelper
 */
class DateTimeHelper
{
    /**
     * @var DateTimeFormatter
     */
    private $formatter;

    /**
     * DateTimeHelper constructor.
     * @param DateTimeFormatter $formatter
     */
    public function __construct(DateTimeFormatter $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * Returns a single number of years, months, days, hours, minutes or
     * seconds between the specified date times.
     *
     * @param  mixed $since The datetime for which the diff will be calculated
     * @param  mixed $since The datetime from which the diff will be calculated
     *
     * @return string
     */
    public function diff(\DateTimeInterface $from, \DateTimeInterface $to = null)
    {
        if ($to === null) {
            $to = new \DateTime();
        }
        return $this->formatter->formatDiff($from, $to);
    }

}