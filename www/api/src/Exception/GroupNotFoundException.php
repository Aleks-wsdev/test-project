<?php

declare(strict_types=1);

namespace App\Exception;

class GroupNotFoundException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Group not found');
    }
}
