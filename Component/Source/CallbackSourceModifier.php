<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 27/03/20
 * Time: 22:44
 */

namespace Umbrella\CoreBundle\Component\Source;

/**
 * Class CallbackSourceModifier
 */
class CallbackSourceModifier extends AbstractSourceModifier
{
    /**
     * @var callable
     */
    private $callback;

    /**
     * SourceModifier constructor.
     * @param callable $callback
     * @param int $priority
     */
    public function __construct(callable $callback, $priority = 0)
    {
        parent::__construct($priority);
        $this->callback = $callback;
    }

    /**
     * @param array $args
     * @return mixed
     */
    public function modify(array $args)
    {
        call_user_func_array($this->callback, $args);
    }
}