<?php

namespace Umbrella\CoreBundle\Component\DateTime;

use DatetimeInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class DateTimeFormatter
 */
class DateTimeFormatter
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * Constructor
     *
     * @param TranslatorInterface $translator Translator used for messages
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Returns a formatted diff for the given from and to datetimes
     *
     * @param DateTimeInterface $from
     * @param DateTimeInterface $to
     *
     * @return string
     */
    public function formatDiff(DateTimeInterface $from, DateTimeInterface $to)
    {
        static $units = [
            'y' => 'year',
            'm' => 'month',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        ];

        $diff = $to->diff($from);

        foreach ($units as $attribute => $unit) {
            $count = $diff->$attribute;
            if (0 !== $count) {
                return $this->doGetDiffMessage($count, $diff->invert, $unit);
            }
        }

        return $this->getEmptyDiffMessage();
    }

    /**
     * Returns the diff message for the specified count and unit
     *
     * @param int  $count  The diff count
     * @param bool $invert Whether to invert the count
     * @param int  $unit   The unit must be either year, month, day, hour,
     *                     minute or second
     *
     * @return string
     */
    public function getDiffMessage($count, $invert, $unit)
    {
        if (0 === $count) {
            throw new \InvalidArgumentException('The count must not be null.');
        }

        $unit = strtolower($unit);

        if (!in_array($unit, ['year', 'month', 'day', 'hour', 'minute', 'second'])) {
            throw new \InvalidArgumentException(sprintf('The unit \'%s\' is not supported.', $unit));
        }

        return $this->doGetDiffMessage($count, $invert, $unit);
    }

    /**
     * Returns the message for an empty diff
     *
     * @return string
     */
    public function getEmptyDiffMessage()
    {
        return $this->translator->trans('diff.empty', [], 'time');
    }

    protected function doGetDiffMessage($count, $invert, $unit)
    {
        $id = sprintf('diff.%s.%s', $invert ? 'ago' : 'in', $unit);

        return $this->translator->trans($id, ['%count%' => $count], 'time');
    }
}
