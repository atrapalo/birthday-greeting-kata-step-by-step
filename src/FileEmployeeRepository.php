<?php

class FileEmployeeRepository
{
    public function findEmployeesWhoseBirthdayIs(XDate $xDate, $fileName)
    {
        $fileHandler = fopen($fileName, 'r');
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