<?php

declare(strict_types=1);

namespace App\Controller;

use App\Attribute\RequestBody;
use App\Model\ErrorResponse;
use App\Model\GroupDetails;
use App\Model\GroupListReport;
use App\Model\UpdateGroupRequest;
use App\Services\GroupService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api', name: 'group-api')]
final class GroupController extends AbstractController
{
    public function __construct(private readonly GroupService $groupService)
    {
    }

    #[Route(path: '/v1/group/{id}', methods: ['GET'])]
    #[OA\Tag(name: 'Group API')]
    #[OA\Response(response: 200, description: 'Returns group detail information', attachables: [new Model(type: GroupDetails::class)])]
    #[OA\Response(response: 404, description: 'group not found', attachables: [new Model(type: ErrorResponse::class)])]
    public function groupById(int $id): Response
    {
        return $this->json($this->groupService->getGroupById($id));
    }

    #[Route(path: '/v1/list-groups-users/', name: '_list-groups-users', methods: ['GET'])]
    #[OA\Tag(name: 'Group API')]
    #[OA\Response(response: 200, description: 'list of groups with users', attachables: [new Model(type: GroupListReport::class)])]
    public function listGroupsUsers(GroupService $groupService): JsonResponse
    {
        return $this->json(
            $groupService->listUsersByGroups(),
        );
    }

    #[Route(path: '/v1/group/update/{id}', methods: ['PUT'])]
    #[OA\Tag(name: 'Group API')]
    #[OA\Response(response: 200, description: 'Update a group')]
    #[OA\Response(response: 400, description: 'Validation failed', attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\Response(response: 404, description: 'Group not found', attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\RequestBody(attachables: [new Model(type: UpdateGroupRequest::class)])]
    public function updateGroup(int $id, #[RequestBody] UpdateGroupRequest $request): JsonResponse
    {
        $this->groupService->updateGroup($id, $request);

        return $this->json(null);
    }

    #[Route('/v1/group/create', name: '_create-group', methods: ['POST'])]
    #[OA\Tag(name: 'Group API')]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property('name', type: 'string'),
            ],
        ),
    )]
    #[OA\Response(response: 201, description: 'Returns the given group', attachables: [new Model(type: GroupDetails::class)])]
    #[OA\Response(response: 404, description: 'Group not found', attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\Response(response: 400, description: 'Duplicate email', attachables: [new Model(type: ErrorResponse::class)])]
    public function createGroup(Request $request, GroupService $groupService): JsonResponse
    {
        $content = json_decode($request->getContent(), true);

        $name = $content['name'] ?? '';

        $group = $groupService->createGroup($name);

        return $this->json($group, Response::HTTP_CREATED);
    }

    #[Route(path: '/v1/group/{id}', methods: ['DELETE'])]
    #[OA\Tag(name: 'Group API')]
    #[OA\Response(response: 200, description: 'Remove a group')]
    #[OA\Response(response: 404, description: 'Group not found', attachables: [new Model(type: ErrorResponse::class)])]
    public function deleteGroup(int $id): JsonResponse
    {
        $this->groupService->deleteGroup($id);

        return $this->json(null);
    }
}
