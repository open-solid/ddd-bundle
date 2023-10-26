<?php

use Ddd\Domain\Event\DomainEventPublisher;
use Ddd\Domain\Event\NativeDomainEventPublisher;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('ddd.domain_event.native_publisher', NativeDomainEventPublisher::class)

        ->alias(DomainEventPublisher::class, 'ddd.domain_event.native_publisher')
    ;
};
