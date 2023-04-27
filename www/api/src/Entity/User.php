<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`users`')]
class User implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer', nullable: false)]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    public ?string $name;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    public ?string $email;

    #[ORM\ManyToMany(targetEntity: Group::class, inversedBy: 'users')]
    #[ORM\JoinTable(name: 'users_groups')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[ORM\InverseJoinColumn(name: 'group_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    public Collection $groups;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
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

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroups(array $groups): self
    {
        foreach ($groups as $Group) {
            if (!$Group instanceof Group) {
                continue;
            }

            if (!$this->groups->contains($Group)) {
                $this->groups[] = $Group;
            }
        }

        return $this;
    }

    public function removeGroups(array $groups): self
    {
        foreach ($groups as $Group) {
            if (!$Group instanceof Group) {
                continue;
            }

            if ($this->groups->contains($Group)) {
                $this->groups->removeElement($Group);
            }
        }

        return $this;
    }

    public function clearGroups(): self
    {
        if (!$this->groups->isEmpty()) {
            $this->groups->clear();
        }

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'groups' => $this->getGroupsToArray(),
        ];
    }

    public function getGroupsToArray(): array
    {
        return array_map(function (Group $group) {
            return [
                'id' => $group->getId(),
                'name' => $group->getName(),
            ];
        }, $this->getGroups()->toArray());
    }
}
