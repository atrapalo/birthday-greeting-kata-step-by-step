<?php

class Notifier
{
    /**
     * @var MessageSender
     */
    private $messageSender;

    public function __construct(MessageSender $messageSender)
    {
        $this->messageSender = $messageSender;
    }

    public function sendGreetingTo(Employee $anEmployee)
    {
        $recipient = $anEmployee->getEmail();
        $body = sprintf('Happy Birthday, dear %s!', $anEmployee->getFirstName());
        $subject = 'Happy Birthday!';

        $this->messageSender->send($subject, 'sender@here.com', $recipient, $body);
    }
}