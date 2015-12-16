<?php

namespace Application\MainBundle\Common;

/**
 * Email sender
 */
class EmailSender {

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;
    
    public function __construct(\Swift_Mailer $mailer) {
        $this->mailer = $mailer;
    }

    /**
     * Main sender function
     * 
     * @return boolean
     */
    public function send() {

        $from = 'siciarek@gmail.com';
        $to = 'siciarek@gmail.com';

        $subject = 'Test message ' . date('Y-m-d H:i:s');

        $body ="

<h1>Test message</h1>
                
<p>Test message.</p>
    
";                
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
}
