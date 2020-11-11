<?php

namespace Umbrella\CoreBundle\Component\Schedule\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Umbrella\CoreBundle\Component\Schedule\JobHelper;

/**
 * Class ScheduleExtension
 */
class ScheduleExtension extends AbstractExtension
{
    /**
     * @var JobHelper
     */
    private $jobHelper;

    /**
     * JobTwigExtension constructor.
     *
     * @param JobHelper $jobHelper
     */
    public function __construct(JobHelper $jobHelper)
    {
        $this->jobHelper = $jobHelper;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('job_state_label', [$this->jobHelper, 'getStateLabel']),
            new TwigFilter('job_state_color', [$this->jobHelper, 'getStateColor']),
            new TwigFilter('job_state_icon', [$this->jobHelper, 'getStateIcon']),
            new TwigFilter('render_job_state', [$this->jobHelper, 'renderState'], ['is_safe' => ['html']]),
            new TwigFilter('render_job_runtime', [$this->jobHelper, 'renderRuntime'], ['is_safe' => ['html']]),
        ];
    }
}