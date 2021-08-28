<?php
declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\SymfonyIntegration\KernelEventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;

final class RequestListener
{
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if ('json' === $request->getContentType()) {
            $data = json_decode((string) $request->getContent(), true)  ?? [];
            $request->request->replace($data);
        }
    }
}