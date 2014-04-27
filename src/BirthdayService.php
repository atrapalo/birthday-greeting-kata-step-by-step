<?php

class BirthdayService
{
    /**
     * @var FileEmployeeRepository
     */
    private $repository;

    /**
     * @var Notifier
     */
    private $notifier;

    /**
     * Class constructor
     *
     * @param EmployeeRepository $aFileEmployeeRepository
     * @param Notifier $aNotifier
     */
    public function __construct(EmployeeRepository $aFileEmployeeRepository, Notifier $aNotifier)
    {
        $this->repository = $aFileEmployeeRepository;
        $this->notifier = $aNotifier;
    }

    public function sendGreetings(XDate $xDate)
    {
        $employees = $this->repository->findEmployeesWhoseBirthdayIs($xDate);

        foreach ($employees as $employee) {
            $this->notifier->sendGreetingTo($employee);
        }
    }
}