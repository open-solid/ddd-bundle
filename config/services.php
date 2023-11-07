<?php

use Ddd\Domain\Event\DomainEventBus;
use Ddd\Domain\Event\NativeDomainEventBus;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Yceruto\Messenger\Bus\NativeMessageBus;
use Yceruto\Messenger\Handler\HandlersCountPolicy;
use Yceruto\Messenger\Middleware\HandleMessageMiddleware;
use Yceruto\Messenger\Middleware\LogMessageMiddleware;
use function Symfony\Component\DependencyInjection\Loader\Configurator\abstract_arg;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('ddd.logger_middleware', LogMessageMiddleware::class)
            ->args([
                service('logger'),
            ])
            ->tag('ddd.domain_event.middleware')

        ->set('ddd.domain_event.subscriber_middleware', HandleMessageMiddleware::class)
            ->args([
                abstract_arg('ddd.domain_event.subscribers_locator'),
                HandlersCountPolicy::NO_HANDLER,
            ])
            ->tag('ddd.domain_event.middleware')

        ->set('ddd.message_bus.domain_event', NativeMessageBus::class)
            ->args([
                tagged_iterator('ddd.domain_event.middleware'),
            ])

        ->set(NativeDomainEventBus::class)
            ->args([
                service('ddd.message_bus.domain_event')
            ])

        ->alias(DomainEventBus::class, NativeDomainEventBus::class)
    ;
};
