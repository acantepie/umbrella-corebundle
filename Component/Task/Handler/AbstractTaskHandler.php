<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 12/09/18
 * Time: 23:29
 */

namespace Umbrella\CoreBundle\Component\Task\Handler;

/**
 * Class AbstractTaskHandler
 */
abstract class AbstractTaskHandler implements TaskHandlerInterface
{
    /**
     * @var TaskHandlerHelper
     */
    protected $helper;

    /**
     * @inheritdoc
     */
    public abstract function getAlias();

    /**
     * @inheritdoc
     */
    public function initialize(TaskHandlerHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
    }

    /**
     * @inheritdoc
     */
    public function destroy()
    {
    }
}
