<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 14/09/18
 * Time: 23:20
 */

namespace Umbrella\CoreBundle\Component\Task\Handler;

/**
 * Interface TaskHandlerInterface
 */
interface TaskHandlerInterface
{
    /**
     * Alias of handler
     *
     * @return string
     */
    public function getAlias();

    /**
     * Method to initialize handler
     *
     * @param TaskHandlerHelper $helper
     */
    public function initialize(TaskHandlerHelper $helper);

    /**
     * Method to execute handler operations
     */
    public function execute();


    /**
     * Method called when task is finished
     */
    public function destroy();
}
