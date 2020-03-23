<?php


namespace Umbrella\CoreBundle\Services;

use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class DateFormater
 * @package Umbrella\CoreBundle\Services
 *
 * Manipulation des dates avec prise en charge de la langue
 */
class DateFormater
{
    /** @var TranslatorInterface */
    private $translator;

    const NUMBER_TO_DAY = [
        1 => "Monday",
        2 => "Tuesday",
        3 => "Wednesday",
        4 => "Thursday",
        5 => "Friday",
        6 => "Saturday",
        7 => "Sunday"
    ];

    const NUMBER_MONTH = [
        1 => "January",
        2 => "February",
        3 => "March",
        4 => "April",
        5 => "May",
        6 => "June",
        7 => "July",
        8 => "August",
        9 => "September",
        10 => "October",
        11 => "November",
        12 => "December"
    ];

    /**
     * DateFormater constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Transforme un objet datetime en date texte traduite ex:
     * 24/12/2020 = Jeudi 24 décembre 2020
     * @param \DateTime $date
     */
    public function dateToPlainText(\DateTime $date, $locale = null): string {
        $day = $this->translator->trans("dateFormater.day.".self::NUMBER_TO_DAY[$date->format("N")], [], null, $locale);
        $month = $this->translator->trans("dateFormater.month.".self::NUMBER_MONTH[$date->format("n")], [], null, $locale);

        return $day." ".$date->format("d")." ".$month." ".$date->format("Y");
    }
}