<?php

declare(strict_types=1);

namespace App\Controller;

use App\Attribute\RequestBody;
use App\Model\ErrorResponse;
use App\Model\UpdateUserRequest;
use App\Model\UserDetails;
use App\Services\UserService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('api', name: 'user-api')]
final class UserController extends AbstractController
{
    public function __construct(private readonly UserService $userService)
    {
    }

    #[Route(path: '/v1/user/{id}', methods: ['GET'])]
    #[OA\Tag(name: 'User API')]
    #[OA\Response(response: 200, description: 'Returns  user detail information', attachables: [new Model(type: UserDetails::class)])]
    #[OA\Response(response: 404, description: 'user not found', attachables: [new Model(type: ErrorResponse::class)])]
    public function userById(int $id): JsonResponse
    {
        return $this->json($this->userService->getUserById($id));
    }

    #[Route(path: '/v1/users/list/', name: '_users-list', methods: ['GET'])]
    #[OA\Tag(name: 'User API')]
    #[OA\Parameter(name: 'name', description: 'Name group value', in: 'query', required: true, schema: new OA\Schema(type: 'string'))]
    #[OA\Response(response: 200, description: 'List of users in the group', attachables: [new Model(type: UserDetails::class)])]
    public function listUsersByGroup(Request $request, UserService $userService): JsonResponse
    {
        return $this->json(
            $userService->listUsersByGroup(
                $request->query->get('name', 0),
            ),
        );
    }

    #[Route(path: '/v1/user/{id}', methods: ['DELETE'])]
    #[OA\Tag(name: 'User API')]
    #[OA\Response(response: 200, description: 'Remove a user')]
    #[OA\Response(response: 404, description: 'user not found', attachables: [new Model(type: ErrorResponse::class)])]
    public function deleteUser(int $id): JsonResponse
    {
        $this->userService->deleteUser($id);

        return $this->json(null);
    }

    #[Route('/v1/user/create', name: '_create-user', methods: ['POST'])]
    #[OA\Tag(name: 'User API')]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property('name', type: 'string'),
                new OA\Property('email', type: 'string'),
                new OA\Property(
                    'groups',
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property('name', type: 'string'),
                        ],
                        type: 'object',
                    ),
                ),
            ],
        ),
    )]
    #[OA\Response(response: 201, description: 'Returns the given user', attachables: [new Model(type: UserDetails::class)])]
    #[OA\Response(response: 404, description: 'Group not found', attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\Response(response: 400, description: 'Duplicate email', attachables: [new Model(type: ErrorResponse::class)])]
    public function createUser(Request $request, UserService $userService, ValidatorInterface $validator): JsonResponse
    {
        $content = json_decode($request->getContent(), true);

        $object = new \stdClass();
        $object->name = $content['name'] ?? '';
        $object->email = $content['email'] ?? '';
        $object->groups = $content['groups'] ?? [];

        $errors = $validator->validate($object);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return new Response($errorsString);
        }
        $user = $userService->createUser($object);

        return $this->json($user, Response::HTTP_CREATED);
    }

    #[Route(path: '/v1/user/update/{id}', methods: ['PUT'])]
    #[OA\Tag(name: 'User API')]
    #[OA\Response(response: 200, description: 'Update a user')]
    #[OA\Response(response: 400, description: 'Validation failed', attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\RequestBody(attachables: [new Model(type: UpdateUserRequest::class)])]
    public function updateUser(int $id, #[RequestBody] UpdateUserRequest $request): JsonResponse
    {
        $this->userService->updateUser($id, $request);

        return $this->json(null);
    }
}
