<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Group;
use App\Exception\InvalidException;
use App\Model\GroupDetails;
use App\Model\GroupListReport;
use App\Model\UpdateGroupRequest;
use App\Model\UserReportItem;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;

class GroupService
{
    public function __construct(private readonly GroupRepository $groupRepository, private readonly UserRepository $userRepository)
    {
    }

    public function getGroupById(int $id): ?GroupDetails
    {
        $group = $this->groupRepository->getGroupById($id);

        return (new GroupDetails())
            ->setId($group->getId())
            ->setName($group->getName())
        ;
    }

    public function listUsersByGroups()
    {
        $listNameCroups = $this->groupRepository->findAllName();

        if (!empty($listNameCroups)) {
            $groups = [];

            foreach ($listNameCroups as $name) {
                $groups[] = (new GroupListReport(
                    array_map(
                        $this->map(...),
                        $this->userRepository->findUsersByGroup($name['name']),
                    ),
                ))->setName($name['name']);
            }
        }

        return $groups;
    }

    private function map($user): UserReportItem
    {
        return (new UserReportItem())
            ->setId($user->getId())
            ->setName($user->getName())
            ->setEmail($user->getEmail())
        ;
    }

    public function createGroup($name): ?Group
    {
        if (!is_string($name)) {
            throw new InvalidException('Invalid name format');
        }

        if (empty($name)) {
            throw new InvalidException('Name is not given');
        }

        $group = $this->groupRepository->findOneByName($name);
        if ($group) {
            throw new InvalidException(' Duplicate name');
        }
        $group = new Group();
        $group->setName($name);

        $this->groupRepository->saveAdd($group);

        return $group;
    }

    public function updateGroup(int $id, UpdateGroupRequest $updateRequest): void
    {
        if (empty($updateRequest->getName())) {
            throw new InvalidException('Name is not given');
        }

        $group = $this->groupRepository->findOneByName($updateRequest->getName());
        if ($group) {
            throw new InvalidException(' Duplicate name');
        }

        $group = $this->groupRepository->getGroupById($id);

        $group->setName($updateRequest->getName());

        $this->groupRepository->saveAndCommit($group);
    }

    public function deleteGroup(int $id): void
    {
        $group = $this->groupRepository->getGroupById($id);

        $this->groupRepository->removeAndCommit($group);
    }
}
