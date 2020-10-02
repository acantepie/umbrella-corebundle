<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 19/01/19
 * Time: 17:14
 */

namespace Umbrella\CoreBundle\Component\Task\Extension;

use Umbrella\CoreBundle\Entity\Task;
use Symfony\Contracts\Translation\TranslatorInterface;
use Umbrella\CoreBundle\Component\DateTime\DateTimeHelper;

/**
 * Class TaskHelper
 */
class TaskHelper
{
    private static $STATE_COLORS = [
        Task::STATE_NEW => 'dark',
        Task::STATE_PENDING => 'primary',
        Task::STATE_RUNNING => 'info',
        Task::STATE_FINISHED => 'success',
        Task::STATE_TERMINATED => 'danger',
        Task::STATE_FAILED => 'danger',
        Task::STATE_CANCELED => 'dark'
    ];

    private static $STATE_ICONS = [
        Task::STATE_NEW => null,
        Task::STATE_PENDING => 'mdi mdi-clock',
        Task::STATE_RUNNING => 'mdi mdi-spin mdi-loading',
        Task::STATE_FINISHED => 'mdi mdi-check',
        Task::STATE_TERMINATED => 'mdi mdi-stop',
        Task::STATE_CANCELED => 'mdi mdi-cancel',
        Task::STATE_FAILED => 'mdi mdi-alert',
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
     * @param  Task        $task
     * @return null|string
     */
    public function renderRuntime(Task $task)
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
}
