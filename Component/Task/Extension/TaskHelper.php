<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 19/01/19
 * Time: 17:14
 */

namespace Umbrella\CoreBundle\Component\Task\Extension;

use Umbrella\CoreBundle\Entity\BaseTask;
use Symfony\Contracts\Translation\TranslatorInterface;
use Umbrella\CoreBundle\Component\DateTime\DateTimeHelper;

/**
 * Class TaskHelper
 */
class TaskHelper
{
    const PROGRESS = '<div class="progress progress-xs m-y-0"><div class="progress-bar progress-bar-striped %s" style="width: %d%%"></div></div> <div class="text-center"> <small class="text-muted">%s %%</small></div>';

    private static $STATE_COLORS = [
        BaseTask::STATE_NEW => 'dark',
        BaseTask::STATE_PENDING => 'primary',
        BaseTask::STATE_RUNNING => 'info',
        BaseTask::STATE_FINISHED => 'success',
        BaseTask::STATE_TERMINATED => 'danger',
        BaseTask::STATE_FAILED => 'danger',
        BaseTask::STATE_CANCELED => 'dark'
    ];

    private static $STATE_ICONS = [
        BaseTask::STATE_NEW => null,
        BaseTask::STATE_PENDING => 'mdi mdi-clock',
        BaseTask::STATE_RUNNING => 'mdi mdi-spin mdi-loading',
        BaseTask::STATE_FINISHED => 'mdi mdi-check',
        BaseTask::STATE_TERMINATED => 'mdi mdi-stop',
        BaseTask::STATE_CANCELED => 'mdi mdi-cancel',
        BaseTask::STATE_FAILED => 'mdi mdi-alert',
    ];

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var DateTimeHelper
     */
    private $dateTimeHelper;

    /**
     * TaskHelper constructor.
     * @param TranslatorInterface $translator
     * @param DateTimeHelper      $dateTimeHelper
     */
    public function __construct(TranslatorInterface $translator, DateTimeHelper $dateTimeHelper)
    {
        $this->translator = $translator;
        $this->dateTimeHelper = $dateTimeHelper;
    }

    /**
     * @param $state
     * @return string
     */
    public function getStateColor($state)
    {
        return self::$STATE_COLORS[$state];
    }

    /**
     * @param $state
     * @return string
     */
    public function getStateLabel($state)
    {
        return $this->translator->trans(sprintf('task.state.%s', $state));
    }

    /**
     * @param $state
     * @return string
     */
    public function getStateIcon($state)
    {
        return self::$STATE_ICONS[$state];
    }

    /**
     * @param $state
     * @return string
     */
    public function renderState($state)
    {
        $icon = $this->getStateIcon($state);

        return $icon
            ? sprintf('<span class="badge badge-%s"><i class="%s mr-1"></i> %s</span>', $this->getStateColor($state), $icon, $this->getStateLabel($state))
            : sprintf('<span class="badge badge-%s">%s</span>', $this->getStateColor($state), $this->getStateLabel($state));
    }

    /**
     * @param  BaseTask    $task
     * @return null|string
     */
    public function renderRuntime(BaseTask $task)
    {
        // running
        if ($task->isRunning()) {
            return sprintf(
                '<span>%s</span> <br> <span class="text-muted">%s</span>',
                $this->dateTimeHelper->diff($task->startedAt),
                $task->runtime()
            );
        }

        // not started
        if ($task->isPending() || $task->isNew() || $task->isCanceled()) {
            return null;
        }

        // done
        return sprintf(
            '<span>%s</span> <br> <span class="text-muted">%s</span>',
            $task->startedAt->format('d/m/Y H:i'),
            $task->runtime()
        );
    }

    /**
     * @param  BaseTask    $task
     * @return null|string
     */
    public function renderProgress(BaseTask $task)
    {
        if ($task->progress === null) {
            return null;
        }

        $color = $this->getStateColor($task->state);

        if ($task->isRunning()) {
            $color .= ' progress-bar-animated';
        }

        return sprintf(self::PROGRESS, $color, $task->progress, $task->progress);
    }
}
