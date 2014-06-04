<?php
namespace User\Bundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;

class StaffSecurityFactory implements SecurityFactoryInterface
{
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {

        $providerId = 'staff.security.authentication_provider.' . $id;
        $listenerId = 'staff.security.authentication_listener.' . $id;

        $container->setDefinition($providerId, new DefinitionDecorator('staff.security.authentication_provider'));
        $container->setDefinition($listenerId, new DefinitionDecorator('staff.security.authentication_listener'));

        return array($providerId, $listenerId, $defaultEntryPoint);
    }

    public function getPosition()
    {
        return 'pre_auth';
    }

    public function getKey()
    {
        return 'staff_security';
    }

    public function addConfiguration(NodeDefinition $node)
    {
    }
}