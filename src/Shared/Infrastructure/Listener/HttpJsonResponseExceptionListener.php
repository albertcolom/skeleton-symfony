<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Listener;

use InvalidArgumentException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Throwable;

class HttpJsonResponseExceptionListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException'
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();
        $httpStatusCode = $this->getHttpResponseCode($throwable);
        $httpStatusMessage = $this->getHttpStatusMessage($httpStatusCode);

        $jsonResponse = new JsonResponse(
            [
                'code' => $httpStatusCode,
                'status' => $httpStatusMessage,
                'message' => $throwable->getMessage()
            ],
            $httpStatusCode
        );

        $event->setResponse($jsonResponse);
    }

    private function getHttpResponseCode(Throwable $throwable): int
    {
        if ($throwable->getCode()) {
            return $this->getValidHttpCodeStatus($throwable->getCode());
        }

        if ($throwable instanceof HttpException) {
            return $this->getValidHttpCodeStatus($throwable->getStatusCode());
        }

        if ($throwable instanceof InvalidArgumentException) {
            return Response::HTTP_BAD_REQUEST;
        }

        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }

    private function getValidHttpCodeStatus(int $httpStatusCode): int
    {
        if (isset(Response::$statusTexts[$httpStatusCode])) {
            return $httpStatusCode;
        }

        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }

    private function getHttpStatusMessage(int $httpStatusCode): string
    {
        if (isset(Response::$statusTexts[$httpStatusCode])) {
            return Response::$statusTexts[$httpStatusCode];
        }

        return 'Whoops, looks like something went wrong.';
    }
}
