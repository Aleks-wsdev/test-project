<?php

declare(strict_types=1);

namespace App\Model;

class UpdateGroupRequest
{
    private ?string $name = null;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
