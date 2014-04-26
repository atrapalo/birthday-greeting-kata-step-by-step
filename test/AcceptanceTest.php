<?php

class AcceptanceTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var int
     */
    private static $SMTP_PORT = 25;

    /**
     * @var MessageSenderSpy
     */
    private $messageSenderSpy;

    /**
     * @var BirthdayService
     */
    private $service;

    public function setUp()
    {
        $this->messageSenderSpy = new MessageSenderSpy();

        $this->service = new BirthdayService(
            new FileEmployeeRepository(__DIR__ . '/resources/employee_data.txt'),
            $this->messageSenderSpy
        );
    }

    public function tearDown()
    {
        $this->service = $this->messageSenderSpy = null;
    }

    /**
     * @test
     */
    public function willSendGreetings_whenItsSomebodysBirthday()
    {
        $this->service->sendGreetings(new XDate('2008/10/08'));

        $messagesSent = $this->messageSenderSpy->getMessagesSent();
        $this->assertCount(1, $messagesSent, 'message not sent?');
        $message = $messagesSent[0];
        $this->assertEquals('Happy Birthday, dear John!', $message->getBody());
        $this->assertEquals('Happy Birthday!', $message->getSubject());
        $this->assertCount(1, $message->getTo());
        $this->assertEquals('john.doe@foobar.com', array_keys($message->getTo())[0]);
    }

    /**
     * @test
     */
    public function willNotSendEmailsWhenNobodysBirthday()
    {
        $this->service->sendGreetings(new XDate('2008/01/01'));

        $this->assertCount(0, $messagesSent = $this->messageSenderSpy->getMessagesSent(), 'what? messages?');
    }
}

class MessageSenderSpy implements MessageSender
{
    private $messagesSent = [];

    public function send($subject, $sender, $recipient, $body)
    {
        $msg = Swift_Message::newInstance($subject);
        $msg
            ->setFrom($sender)
            ->setTo([$recipient])
            ->setBody($body)
        ;

        $this->messagesSent[] = $msg;
    }

    /**
     * @return array
     */
    public function getMessagesSent()
    {
        return $this->messagesSent;
    }
}