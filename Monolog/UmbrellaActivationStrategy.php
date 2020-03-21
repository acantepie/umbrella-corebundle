<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 26/12/18
 * Time: 15:43
 */

namespace  Umbrella\CoreBundle\Monolog;

use Monolog\Handler\FingersCrossed\ActivationStrategyInterface;

/**
 * Class UmbrellaActivationStrategy
 * @package Umbrella\CoreBundle\Monolog
 */
class UmbrellaActivationStrategy implements ActivationStrategyInterface
{
    /**
     * @var boolean
     */
    private $enable;

    /**
     * FransatActivationStrategy constructor.
     * @param bool $enable
     */
    public function __construct($enable = true)
    {
        $this->enable = $enable;
    }

    /**
     * Returns whether the given record activates the handler.
     *
     * @param  array $record
     * @return bool
     */
    public function isHandlerActivated(array $record)
    {
        return $this->enable;
    }
}
