<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 12/09/18
 * Time: 23:29
 */

namespace Umbrella\CoreBundle\Component\Task\Handler;

use Umbrella\CoreBundle\Entity\BaseTaskConfig;

/**
 * Class AbstractTaskHandler
 */
abstract class AbstractTaskHandler
{
    /**
     * Method to initialize handler
     * @param BaseTaskConfig $config
     */
    public function initialize(BaseTaskConfig $config)
    {
    }

    /**
     * Method to execute handler operations
     * @param BaseTaskConfig $config
     */
    public function execute(BaseTaskConfig $config)
    {
    }

    /**
     * Method called when task is finished
     * @param BaseTaskConfig $config
     */
    public function destroy(BaseTaskConfig $config)
    {
    }
}
