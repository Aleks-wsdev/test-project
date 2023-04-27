<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: '`groups`')]

class Group implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer', nullable: false)]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    public ?string $name = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'groups', fetch: 'EXTRA_LAZY')]
    public Collection $users;

    public function __construct(?string $name = null, ...$users)
    {
        $this->name = $name;
        $this->users = new ArrayCollection($users);
    }

    public function __toString(): string
    {
        return sprintf('#%s. %s', $this->getId(), $this->getName());
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $user->addGroups([$this]);
            $this->users[] = $user;
        }

        return $this;
    }

    public function clearUsers(): self
    {
        if (!$this->users->isEmpty()) {
            $this->users->clear();
        }

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'users' => $this->getUsersToArray(),
        ];
    }

    public function getUsersToArray(): array
    {
        return array_map(function (User $user) {
            return [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
            ];
        }, $this->getUsers()->toArray());
    }
}
