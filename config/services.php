<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use DataDog\AuditBundle\Command\AuditLogDeleteOldLogsCommand;
use DataDog\AuditBundle\EventListener\AuditListener;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\Events;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

return static function (ContainerConfigurator $container) {
    // @formatter:off
    $services = $container->services();
    $services
        ->set('data_dog_audit.listener.audit', AuditListener::class)->private()
        ->arg(0, new Reference(TokenStorageInterface::class))
        ->tag('doctrine.event_listener', ['event' => Events::onFlush])
    ;

    $services->set('data_dog_audit.command.delete_old_logs', AuditLogDeleteOldLogsCommand::class)
        ->arg(0, new Reference(Connection::class))
        ->tag('console.command')
    ;
    // @formatter:on
};
