<?php

class FileEmployeeRepository implements EmployeeRepository
{
    /**
     * @var string
     */
    private $fileName;

    /**
     * Class constructor
     *
     * @param string $fileName
     */
    public function __construct($fileName)
    {
        $this->fileName = $fileName;
    }

    public function findEmployeesWhoseBirthdayIs(XDate $xDate)
    {
        $fileHandler = fopen($this->fileName, 'r');
        fgetcsv($fileHandler);

        $employees = [];
        while ($employeeData = fgetcsv($fileHandler, null, ',')) {
            $employeeData = array_map('trim', $employeeData);
            $employee = new Employee($employeeData[1], $employeeData[0], $employeeData[2], $employeeData[3]);

            if ($employee->isBirthday($xDate)) {
                $employees[] = $employee;
            }
        }

        return $employees;
    }
}