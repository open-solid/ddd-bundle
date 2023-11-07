<?php

namespace Yceruto\DddBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Yceruto\DddBundle\Attribute\AsDomainEventSubscriber;
use Yceruto\Messenger\Bridge\Symfony\DependencyInjection\CompilerPass\MessageHandlersLocatorPass;
use Yceruto\Messenger\Bridge\Symfony\DependencyInjection\Configurator\MessageHandlerConfigurator;

class DddBundle extends AbstractBundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new MessageHandlersLocatorPass(
            messageHandlerTagName: 'ddd.domain_event.subscriber',
            messageHandlerMiddlewareId: 'ddd.domain_event.subscriber_middleware',
            allowMultiple: true,
        ));
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        MessageHandlerConfigurator::configure($builder, AsDomainEventSubscriber::class, 'ddd.domain_event.subscriber');

        $container->import('../config/services.php');
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
