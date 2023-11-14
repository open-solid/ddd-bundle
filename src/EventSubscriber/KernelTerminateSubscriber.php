<?php

namespace Yceruto\DddBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Yceruto\Messenger\Bus\FlushableMessageBus;

final readonly class KernelTerminateSubscriber implements EventSubscriberInterface
{
    public function __construct(private FlushableMessageBus $bus)
    {
    }

    public function __invoke(TerminateEvent $event): void
    {
        $this->bus->flush();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::TERMINATE => '__invoke',
        ];
    }
}
