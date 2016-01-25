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

    protected function stripHtml($html)
    {
        $plaintext = $html;
        $plaintext = strip_tags($plaintext);
        $plaintext = preg_replace('/^\s*(\S)/', '$1', $plaintext);

        return $plaintext;
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
    public function getMessage($template, $data = [], $to, $from = null, $message = true)
    {
        $content = [
            'subject' => null,
            'html' => null,
            'plaintext' => null,
        ];

        if (!array_key_exists('app_name', $data)) {
            $data['app_name'] = $this->getContainer()->getParameter('app_name');
        }

        if ($from === null) {
            $from = [
                'name' => $data['app_name'],
                'email' => $this->getContainer()->getParameter('mailer_user'),
            ];
        }

        $twig = $this->getContainer()->get('twig');

        $tmpl = $twig->loadTemplate($template);
        $subject = $tmpl->renderBlock('subject', $data);
        $content['subject'] = $subject;

        $html = $tmpl->renderBlock('body', $data);
        $content['html'] = trim($html);

        $plaintext = $this->stripHtml($content['html']);

        if ($tmpl->hasBlock('plaintext')) {
            $plaintext = $tmpl->renderBlock('plaintext', $data);
        }

        $content['plaintext'] = $plaintext;

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
    public function send(\Swift_Message $message)
    {
        $result = $this->getMailer()->send($message, $failures) === 1;

        if ($result === false) {
            throw new \Exception(json_encode($failures, JSON_PRETTY_PRINT));
        }
        
        return true;
    }

    public function getMailer()
    {
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
