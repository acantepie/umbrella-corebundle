<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 24/10/17
 * Time: 10:36
 */

namespace Umbrella\CoreBundle\Services;

use Psr\Log\LoggerInterface;
use Twig\Environment;

/**
 * Class UmbrellaMailer
 */
class UmbrellaMailer
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var string
     */
    private $from;

    /**
     * @var string
     */
    private $fromName;

    /**
     * UmbrellaMailer constructor.
     * @param Environment $twig
     * @param LoggerInterface $logger
     * @param \Swift_Mailer $mailer
     */
    public function __construct(Environment $twig, LoggerInterface $logger, \Swift_Mailer $mailer)
    {
        $this->twig = $twig;
        $this->logger = $logger;
        $this->mailer = $mailer;
    }

    /**
     * @param array $config
     */
    public function loadConfig(array $config)
    {
        $this->from = trim($config['from']);
        $this->fromName = trim($config['from_name']);
    }

    /**
     * @param $to
     * @param $subject
     * @param $html
     * @param null $from
     * @param array $attachments
     * @throws \Swift_SwiftException
     * @return \Swift_Message
     */
    public function createMail($to, $subject, $html, $from = null, array $attachments = array())
    {
        $swiftMessage = new \Swift_Message();

        if ($from === null) {
            if (!empty($this->fromName)) {
                $from = [$this->from => $this->fromName];
            } else {
                $from = $this->from;
            }
        }
        $swiftMessage
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setBody($html)
            ->setContentType('text/html');

        foreach ($attachments as $attachment) {
            $this->addAttachment($swiftMessage, $attachment);
        }

        return $swiftMessage;
    }

    /**
     * @param \Swift_Message $mail
     * @param array $attachment
     */
    public function addAttachment(\Swift_Message $mail, array $attachment)
    {
        $path = $attachment['path'];
        if (array_key_exists('filename', $attachment)) {
            $filename = $attachment['filename'];
        } else {
            $filename = basename($attachment['path']);
        }
        $mail->attach(\Swift_Attachment::fromPath($path)->setFilename($filename));
    }

    /**
     * @param \Swift_Message $mail
     */
    public function send(\Swift_Message $mail)
    {
        try {
            return $this->mailer->send($mail);
        } catch (\Exception $e) {
            $this->logger->error('[Umbrella mailer] : enable to send mail : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * @param $to
     * @param $subject
     * @param $html
     * @param null $from
     * @param array $attachments
     * @return bool
     */
    public function sendHtmlMail($to, $subject, $html, $from = null, array $attachments = array())
    {
        try {
            $mail = $this->createMail($to, $subject, $html, $from, $attachments);
            return $this->send($mail);
        } catch(\Swift_SwiftException $e) {
            $this->logger->error('[Umbrella mailer] : enable to create mail : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * @param $to
     * @param $subject
     * @param $template
     * @param array $templateParams
     * @param null $from
     * @param array $attachments
     * @return bool
     */
    public function sendViewMail($to, $subject, $template, array $templateParams = array(), $from = null, array $attachments = array())
    {
        return $this->sendHtmlMail($to, $subject, $this->twig->render($template, $templateParams), $from, $attachments);
    }

}
