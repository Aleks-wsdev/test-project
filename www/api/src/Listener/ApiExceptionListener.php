<?php

declare(strict_types=1);

namespace App\Listener;

use App\Model\ErrorDebugDetails;
use App\Model\ErrorResponse;
use App\Services\ExceptionMapping;
use App\Services\ExceptionMappingResolver;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class ApiExceptionListener
{
    public function __construct(
        private readonly ExceptionMappingResolver $resolver,
        private readonly SerializerInterface $serializer,
        private readonly bool $isDebug,
    ) {
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();

        $mapping = $this->resolver->resolve($throwable::class);
        if (null === $mapping) {
            $mapping = ExceptionMapping::fromCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $message = $mapping->isHidden() && !$this->isDebug
            ? Response::$statusTexts[$mapping->getCode()]
            : $throwable->getMessage();

        $details = $this->isDebug ? new ErrorDebugDetails($throwable->getTraceAsString()) : null;
        $data = $this->serializer->serialize(new ErrorResponse($message, $details), JsonEncoder::FORMAT);

        $event->setResponse(new JsonResponse($data, $mapping->getCode(), [], true));
    }
}
