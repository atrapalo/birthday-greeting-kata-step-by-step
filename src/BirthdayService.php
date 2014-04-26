<?php

class BirthdayService
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var FileEmployeeRepository
     */
    private $repository;

    /**
     * @var SwiftMessageSender
     */
    private $notifier;

    /**
     * Class constructor
     *
     * @param EmployeeRepository $aFileEmployeeRepository
     */
    public function __construct(EmployeeRepository $aFileEmployeeRepository)
    {
        $this->repository = $aFileEmployeeRepository;
    }

    public function sendGreetings(XDate $xDate, $smtpHost, $smtpPort)
    {
        $employees = $this->repository->findEmployeesWhoseBirthdayIs($xDate);

        foreach ($employees as $employee) {
            $recipient = $employee->getEmail();
            $body = sprintf('Happy Birthday, dear %s!', $employee->getFirstName());
            $subject = 'Happy Birthday!';
            $this->sendMessage($smtpHost, $smtpPort, 'sender@here.com', $subject, $body, $recipient);
        }
    }

    private function sendMessage($smtpHost, $smtpPort, $sender, $subject, $body, $recipient)
    {
        $this->notifier = new SwiftMessageSender($smtpHost, $smtpPort);

        $msg = $this->notifier->createMessage($subject, $sender, $recipient, $body);

        // Send the message
        $this->doSendMessage($msg);
    }

    // made protected for testing :-(
    protected function doSendMessage(Swift_Message $msg)
    {
        $this->notifier->send($msg);
    }
}