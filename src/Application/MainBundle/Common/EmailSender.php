<?php

namespace Application\MainBundle\Common;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Email sender.
 */
class EmailSender implements ContainerAwareInterface
{
    /**
     * Container reference variable.
     *
     * @var Container interface
     */
    protected $container;

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Main sender function.
     *
     * @return bool
     */
    public function send()
    {
        $from = 'siciarek@gmail.com';
        $to = 'siciarek@gmail.com';

        $subject = sprintf('[%s] Test message %s', $this->getContainer()->getParameter('app_name'), date('Y-m-d H:i:s'));

        $body = '

<h1>Test message</h1>

<p>Test message.</p>

';
        $bodyPlainText = strip_tags($body);

        $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($from)
                ->setTo($to)
                ->setBody($body, 'text/html')
                ->addPart($bodyPlainText, 'text/plain')
        ;

        return $this->mailer->send($message) == 1;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
