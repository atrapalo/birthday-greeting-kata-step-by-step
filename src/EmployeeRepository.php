<?php

interface EmployeeRepository
{
    public function findEmployeesWhoseBirthdayIs(XDate $xDate);
}