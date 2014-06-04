<?php

namespace User\Bundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class UserBundle extends Bundle
{

    public function build(\Symfony\Component\DependencyInjection\ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new \User\Bundle\DependencyInjection\UserSecurityFactory());
        $extension->addSecurityListenerFactory(new \User\Bundle\DependencyInjection\StaffSecurityFactory());
    }
}