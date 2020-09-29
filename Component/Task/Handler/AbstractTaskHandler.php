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
abstract class AbstractTaskHandler
{
    /**
     * @var TaskHandlerHelper
     */
    protected $taskHelper;

    /**
     * Method to initialize handler
     *
     * @param TaskHandlerHelper $helper
     */
    public function initialize(TaskHandlerHelper $taskHelper)
    {
        $this->taskHelper = $taskHelper;
    }

    /**
     * Method to execute handler operations
     */
    public function execute()
    {
    }

    /**
     * Method called when task is finished
     */
    public function destroy()
    {
    }
}
