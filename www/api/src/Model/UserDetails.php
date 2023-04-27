<?php

declare(strict_types=1);

namespace App\Model;

use OpenApi\Attributes as OA;

class UserDetails
{
    private int $id;

    public string $name;

    public string $email;

    #[OA\Property(
        type: 'array',
        items: new OA\Items(
            properties: [
                new OA\Property('name', type: 'string'),
            ],
        ),
    )]
    private ?array $groups;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getGroups(): ?array
    {
        return $this->groups;
    }

    public function setGroups(?array $groups): self
    {
        $this->groups = $groups;

        return $this;
    }
}
