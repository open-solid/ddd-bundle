<?php

use Ddd\Domain\Event\DomainEventBus;
use Ddd\Domain\Event\NativeDomainEventBus;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Yceruto\Messenger\Bus\NativeMessageBus;
use Yceruto\Messenger\Handler\HandlersCountPolicy;
use Yceruto\Messenger\Middleware\HandleMessageMiddleware;
use function Symfony\Component\DependencyInjection\Loader\Configurator\abstract_arg;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('cqs.domain_event.subscriber_middleware', HandleMessageMiddleware::class)
            ->args([
                abstract_arg('cqs.domain_event.subscribers_locator'),
                HandlersCountPolicy::NO_HANDLER,
            ])
            ->tag('cqs.domain_event.middleware')

        ->set('cqs.message_bus.domain_event', NativeMessageBus::class)
            ->args([
                tagged_iterator('cqs.domain_event.middleware'),
            ])

        ->set(NativeDomainEventBus::class)
            ->args([
                service('cqs.message_bus.domain_event')
            ])

        ->alias(DomainEventBus::class, NativeDomainEventBus::class)
    ;
};
