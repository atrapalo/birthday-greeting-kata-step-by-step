<?php

$service = new BirthdayService(
    new FileEmployeeRepository('employee_data.txt'),
    new Notifier(
        new SwiftMessageSender(
            'localhost', 25
        )
    )
);

$service->sendGreetings(new XDate('2008/10/08'));