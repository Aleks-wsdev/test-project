<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\User;
use App\Exception\InvalidException;
use App\Model\UpdateUserRequest;
use App\Model\UserDetails;
use App\Model\UserListItem;
use App\Model\UserListResponse;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;

class UserService
{
    public function __construct(private readonly UserRepository $userRepository, private readonly GroupRepository $groupRepository)
    {
    }

    public function getUserById(int $id): ?UserDetails
    {
        $user = $this->userRepository->getUserById($id);

        return $this->map($user);
    }

    public function listUsersByGroup($name)
    {
        return new UserListResponse(
            array_map(
                $this->map(...),
                $this->userRepository->findUsersByGroup($name),
            ),
        );
    }

    public function listUsers(): UserListResponse
    {
        return new UserListResponse(
            array_map(
                $this->map(...),
                $this->userRepository->findAllUsers(),
            ),
        );
    }

    private function map(User $user): UserListItem
    {
        $groupNames = $user->getGroups()->map(function ($item) {
            return $item->name;
        });

        return (new UserListItem())
            ->setId($user->getId())
            ->setName($user->getName())
            ->setEmail($user->getEmail())
            ->setGroups($groupNames->toArray())
        ;
    }

    public function deleteUser(int $id): void
    {
        $user = $this->userRepository->getUserById($id);

        $this->userRepository->removeAndCommit($user);
    }

    public function createUser(object $dto): ?User
    {
        if (empty($dto->name)) {
            throw new InvalidException('Name is not given');
        }
        if (empty($dto->email)) {
            throw new InvalidException('Email is not given');
        }

        $user = $this->userRepository->findOneByEmail($dto->email);
        if ($user) {
            throw new InvalidException(' Duplicate email');
        }
        $user = new User();
        $user->setName($dto->name);
        $user->setEmail($dto->email);

        if (!empty($dto->groups)) {
            $groups = [];
            foreach ($dto->groups as $group) {
                if ($group['name']) {
                    $groups[] = $this->groupRepository->getGroupByName($group['name']);
                }
            }
            $user->addGroups($groups);
        }

        $this->userRepository->saveAdd($user);

        return $user;
    }

    public function updateUser(int $id, UpdateUserRequest $updateRequest): void
    {
        $user = $this->userRepository->findOneByEmail($updateRequest->getEmail());
        if ($user) {
            throw new InvalidException(' Duplicate email');
        }

        $user = $this->userRepository->getUserById($id);

        $user->setName($updateRequest->getName());
        $user->setEmail($updateRequest->getEmail());

        $this->userRepository->saveAndCommit($user);
    }
}
