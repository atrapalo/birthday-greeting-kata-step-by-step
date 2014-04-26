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
            $this->sendGreetingTo($employee);
        }
    }

    private function sendGreetingTo(Employee $anEmployee)
    {
        $recipient = $anEmployee->getEmail();
        $body = sprintf('Happy Birthday, dear %s!', $anEmployee->getFirstName());
        $subject = 'Happy Birthday!';

        $this->notifier->send($subject, 'sender@here.com', $recipient, $body);
    }
}