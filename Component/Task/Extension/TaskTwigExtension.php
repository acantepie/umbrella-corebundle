<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 19/01/19
 * Time: 21:54
 */

namespace Umbrella\CoreBundle\Component\Task\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class TaskTwigExtension
 */
class TaskTwigExtension extends AbstractExtension
{
    /**
     * @var TaskHelper
     */
    private $taskHelper;

    /**
     * TaskTwigExtension constructor.
     * @param TaskHelper $taskHelper
     */
    public function __construct(TaskHelper $taskHelper)
    {
        $this->taskHelper = $taskHelper;
    }

    public function getFilters()
    {
        return array(
            new TwigFilter('render_task_state', array($this->taskHelper, 'renderState'), ['is_safe' => ['html']]),
            new TwigFilter('render_task_runtime', array($this->taskHelper, 'renderRuntime'), ['is_safe' => ['html']]),
            new TwigFilter('render_task_progress', array($this->taskHelper, 'renderProgress'), ['is_safe' => ['html']]),
        );
    }


}