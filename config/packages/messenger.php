<?php

use OpenSolid\Ddd\Domain\Event\DomainEvent;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Messenger\MessageBusInterface;

return static function (ContainerBuilder $container) {
    if (!interface_exists(MessageBusInterface::class)) {
        return;
    }

    $container->prependExtensionConfig('framework', [
        'messenger' => [
            'buses' => [
                'event.bus' => [
                    'default_middleware' => 'allow_no_handlers',
                    'middleware' => [
                        'router_context',
                    ],
                ],
            ],
            'transports' => [
                'async' => [
                    'dsn' => '%env(MESSENGER_TRANSPORT_DSN)%',
                ],
            ],
            'routing' => [
                DomainEvent::class => 'async',
            ],
        ],
    ]);
};
