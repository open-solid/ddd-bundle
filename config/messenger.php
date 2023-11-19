<?php

use Ddd\Domain\Event\DomainEventBus;
use Ddd\Infrastructure\Event\SymfonyDomainEventBus;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('es.bus', SymfonyDomainEventBus::class)
            ->args([
                service('query.bus'),
            ])

        ->alias(DomainEventBus::class, 'es.bus')
    ;
};
