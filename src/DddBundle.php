<?php

namespace Yceruto\DddBundle;

use LogicException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\Messenger\MessageBusInterface;
use Yceruto\DddBundle\Attribute\AsDomainEventSubscriber;
use Yceruto\Messenger\Bridge\Symfony\DependencyInjection\CompilerPass\MessageHandlersLocatorPass;
use Yceruto\Messenger\Bridge\Symfony\DependencyInjection\Configurator\MessageHandlerConfigurator;

class DddBundle extends AbstractBundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new MessageHandlersLocatorPass('domain_event.subscriber', 'es.middleware.subscriber', true));
    }

    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/packages/messenger.php');
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        if ($config['bus']['strategy'] === 'native') {
            MessageHandlerConfigurator::configure($builder, AsDomainEventSubscriber::class, 'domain_event.subscriber');

            $container->import('../config/native.php');
        } else {
            if (!interface_exists(MessageBusInterface::class)) {
                throw new LogicException('The "symfony" strategy requires symfony/messenger package.');
            }

            MessageHandlerConfigurator::configure($builder, AsDomainEventSubscriber::class, 'messenger.message_handler', ['bus' => 'event.bus']);

            $container->import('../config/messenger.php');
        }
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
