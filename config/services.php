<?php

use Ddd\Domain\Event\DomainEventBus;
use Ddd\Domain\Event\NativeDomainEventBus;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Yceruto\DddBundle\EventSubscriber\KernelTerminateSubscriber;
use Yceruto\Messenger\Bus\NativeLazyMessageBus;
use Yceruto\Messenger\Bus\NativeMessageBus;
use Yceruto\Messenger\Handler\HandlersCountPolicy;
use Yceruto\Messenger\Middleware\HandleMessageMiddleware;
use Yceruto\Messenger\Middleware\LogMessageMiddleware;
use function Symfony\Component\DependencyInjection\Loader\Configurator\abstract_arg;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('es.middleware.logger', LogMessageMiddleware::class)
            ->args([
                service('logger'),
            ])
            ->tag('es.middleware')

        ->set('es.middleware.subscriber', HandleMessageMiddleware::class)
            ->args([
                abstract_arg('es.subscriber.locator'),
                HandlersCountPolicy::NO_HANDLER,
                service('logger'),
            ])
            ->tag('es.middleware')

        ->set('es.bus.native', NativeMessageBus::class)
            ->args([
                tagged_iterator('es.middleware'),
            ])

        ->set('es.bus.native.lazy', NativeLazyMessageBus::class)
            ->args([
                service('es.bus.native'),
            ])

        ->set('es.bus', NativeDomainEventBus::class)
            ->args([
                service('es.bus.native.lazy'),
            ])

        ->alias(DomainEventBus::class, 'es.bus')

        ->set('es.symfony.event_subscriber.terminate', KernelTerminateSubscriber::class)
            ->args([
                service('es.bus'),
            ])
            ->tag('kernel.event_subscriber')
    ;
};
