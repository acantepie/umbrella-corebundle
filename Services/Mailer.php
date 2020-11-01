<?php

namespace Umbrella\CoreBundle\Services;

use Psr\Log\LoggerInterface;

/**
 * Class Mailer
 */
class Mailer
{
    /**
     * @var \Swift_Mailer
     */
    private $swiftMailer;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Mailer constructor.
     *
     * @param \Swift_Mailer   $swiftMailer
     * @param LoggerInterface $logger
     */
    public function __construct(\Swift_Mailer $swiftMailer, LoggerInterface $logger)
    {
        $this->swiftMailer = $swiftMailer;
        $this->logger = $logger;
    }

    /**
     * @param \Swift_Message $message
     *
     * @return bool
     */
    public function send(\Swift_Message $message)
    {
        try {
            $this->swiftMailer->send($message);

            return true;
        } catch (\Exception $e) {
            $this->logger->info('Error occured while sending message : ' . $e->getMessage());

            return false;
        }
    }
}
