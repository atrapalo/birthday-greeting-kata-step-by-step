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
        // Create a mail session
        $this->mailer = $this->createMailer($smtpHost, $smtpPort);

        // Construct the message
        $msg = $this->createMessage($subject, $sender, $recipient, $body);

        // Send the message
        $this->doSendMessage($msg);
    }

    // made protected for testing :-(
    protected function doSendMessage(Swift_Message $msg)
    {
        $this->mailer->send($msg);
    }

    /**
     * @param $smtpHost
     * @param $smtpPort
     * @return Swift_Mailer
     */
    private function createMailer($smtpHost, $smtpPort)
    {
        return Swift_Mailer::newInstance(Swift_SmtpTransport::newInstance($smtpHost, $smtpPort));
    }

    /**
     * @param $subject
     * @param $sender
     * @param $recipient
     * @param $body
     * @return Swift_Message
     */
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
}