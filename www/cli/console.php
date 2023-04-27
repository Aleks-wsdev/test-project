#!/usr/bin/env php
<?php

require __DIR__.'/bootstrap.php';

use Symfony\Component\Console\Application;
use Console\{
    GetUserByIdCommand,
    GetGroupByIdCommand,
    ListGroupsUsersCommand,
    ListUsersByGroupCommand,
    CreateUserCommand,
    UpdateUserCommand,
    DeleteUserCommand,
    CreateGroupCommand,
    UpdateGroupCommand,
    DeleteGroupCommand,
};

$application = new Application('Symfony API client', 'v1.0.0');

$application->add(new GetGroupByIdCommand());
$application->add(new GetUserByIdCommand());
$application->add(new ListGroupsUsersCommand());
$application->add(new ListUsersByGroupCommand());
$application->add(new CreateUserCommand());
$application->add(new UpdateUserCommand());
$application->add(new DeleteUserCommand());
$application->add(new CreateGroupCommand());
$application->add(new UpdateGroupCommand());
$application->add(new DeleteGroupCommand());

try {
    $application->run();
} catch (Exception $e) {
    print_r($e);
}