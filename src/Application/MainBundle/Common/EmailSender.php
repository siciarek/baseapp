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
     * Returns ready to send
     * 
     * @param string $template email template name
     * @param array $data email data
     * @param array $from sender email
     * @param array $to recipient email
     * @return \Swift_Message
     */
    public function getMessage($template, $data = [], $from, $to, $message = true)
    {
        $content = [
            'subject' => null,
            'html' => null,
            'plaintext' => null,
        ];

        $twig = $this->getContainer()->get('twig');

        $tmpl = $twig->loadTemplate($template);
        $subject = $tmpl->renderBlock('subject', $data);
        $content['subject'] = $subject;

        $html = $tmpl->renderBlock('body', $data);
        $content['html'] = trim($html);

        $content['plaintext'] = strip_tags($content['html']);

        if ($message === false) {
            return $content;
        }

        $message = \Swift_Message::newInstance()
                ->setSubject($content['subject'])
                ->setSender($from['email'], $from['name'])
                ->setFrom($from['email'], $from['name'])
                ->setTo($to['email'], $to['name'])
                ->setBody($content['html'], 'text/html')
                ->addPart($content['plaintext'], 'text/plain')
        ;

        return $message;
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

    public function getMailer() {
        return $this->mailer;
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
