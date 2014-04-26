<?php

class BirthdayService
{
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
     * @param SwiftMessageSender $aNotifier
     */
    public function __construct(EmployeeRepository $aFileEmployeeRepository, SwiftMessageSender $aNotifier)
    {
        $this->repository = $aFileEmployeeRepository;
        $this->notifier = $aNotifier;
    }

    public function sendGreetings(XDate $xDate)
    {
        $employees = $this->repository->findEmployeesWhoseBirthdayIs($xDate);

        foreach ($employees as $employee) {
            $recipient = $employee->getEmail();
            $body = sprintf('Happy Birthday, dear %s!', $employee->getFirstName());
            $subject = 'Happy Birthday!';
            $this->sendMessage('sender@here.com', $subject, $body, $recipient);
        }
    }

    private function sendMessage($sender, $subject, $body, $recipient)
    {
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