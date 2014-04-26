<?php

class SwiftMessageSender implements MessageSender
{
    /**
     * @var string
     */
    private $smtpHost;

    /**
     * @var int
     */
    private $smtpPort;

    /**
     * Class constructor
     *
     * @param string $smtpHost
     * @param int $smtpPort
     */
    public function __construct($smtpHost, $smtpPort)
    {
        $this->smtpHost = $smtpHost;
        $this->smtpPort = $smtpPort;
    }

    private function createMailer($smtpHost, $smtpPort)
    {
        return Swift_Mailer::newInstance(Swift_SmtpTransport::newInstance($smtpHost, $smtpPort));
    }

    private function createMessage($subject, $sender, $recipient, $body)
    {
        $msg = Swift_Message::newInstance($subject);
        $msg
            ->setFrom($sender)
            ->setTo([$recipient])
            ->setBody($body)
        ;

        return $msg;
    }

    public function send($subject, $sender, $recipient, $body)
    {
        $mailer = $this->createMailer($this->smtpHost, $this->smtpPort);
        $mailer->send($this->createMessage($subject, $sender, $recipient, $body));
    }
}