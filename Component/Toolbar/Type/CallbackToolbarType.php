<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 01/04/20
 * Time: 23:57
 */

namespace Umbrella\CoreBundle\Component\Toolbar\Type;

use Umbrella\CoreBundle\Component\Toolbar\ToolbarBuilder;

/**
 * Class CallbackToolbarType
 */
class CallbackToolbarType extends ToolbarType
{
    /**
     * @var callable
     */
    private $callback;

    /**
     * CallbackToolbarType constructor.
     * @param $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function buildToolbar(ToolbarBuilder $builder, array $options)
    {
        call_user_func($this->callback, $builder, $options);
    }


}