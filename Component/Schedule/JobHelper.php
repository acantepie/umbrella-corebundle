<?php

namespace Umbrella\CoreBundle\Component\Schedule;

use Symfony\Contracts\Translation\TranslatorInterface;
use Umbrella\CoreBundle\Component\DateTime\DateTimeHelper;
use Umbrella\CoreBundle\Entity\Job;

/**
 * Class JobHelper
 */
class JobHelper
{
    private static $STATE_COLORS = [
        Job::STATE_NEW => 'dark',
        Job::STATE_PENDING => 'primary',
        Job::STATE_RUNNING => 'info',
        Job::STATE_FINISHED => 'success',
        Job::STATE_TERMINATED => 'danger',
        Job::STATE_FAILED => 'danger',
        Job::STATE_CANCELED => 'dark',
    ];

    private static $STATE_ICONS = [
        Job::STATE_NEW => null,
        Job::STATE_PENDING => 'mdi mdi-clock',
        Job::STATE_RUNNING => 'mdi mdi-spin mdi-loading',
        Job::STATE_FINISHED => 'mdi mdi-check',
        Job::STATE_TERMINATED => 'mdi mdi-stop',
        Job::STATE_CANCELED => 'mdi mdi-cancel',
        Job::STATE_FAILED => 'mdi mdi-alert',
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
     * JobHelper constructor.
     *
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
     *
     * @return string
     */
    public function getStateColor($state)
    {
        return self::$STATE_COLORS[$state];
    }

    /**
     * @param $state
     *
     * @return string
     */
    public function getStateLabel($state)
    {
        return $this->translator->trans(sprintf('job.state.%s', $state));
    }

    /**
     * @param $state
     *
     * @return string
     */
    public function getStateIcon($state)
    {
        return self::$STATE_ICONS[$state];
    }

    /**
     * @param $state
     *
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
     * @param Job $job
     *
     * @return string|null
     */
    public function renderRuntime(Job $job)
    {
        // running
        if ($job->isRunning()) {
            return sprintf(
                '<span>%s</span> <br> <span class="text-muted">%s</span>',
                $this->dateTimeHelper->diff($job->startedAt),
                $job->runtime()
            );
        }

        // not started
        if ($job->isPending() || $job->isNew() || $job->isCanceled()) {
            return null;
        }

        // done
        return sprintf(
            '<span>%s</span> <br> <span class="text-muted">%s</span>',
            $job->startedAt->format('d/m/Y H:i'),
            $job->runtime()
        );
    }
}