<?php


namespace Umbrella\CoreBundle\Model;

/**
 * Trait IdTrait
 * @package Umbrella\CoreBundle\Model
 */
trait IdTrait
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

}