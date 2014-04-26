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
     * @param MessageSender $aNotifier
     */
    public function __construct(EmployeeRepository $aFileEmployeeRepository, MessageSender $aNotifier)
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
        $this->notifier->send($subject, $sender, $recipient, $body);
    }
}