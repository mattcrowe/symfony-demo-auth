<?php
namespace User\Bundle\Security;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\User\UserInterface;

class BaseAuthorize
{

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var \Symfony\Component\Security\Core\User\UserInterface
     */
    protected $user;

    /**
     * @var \Symfony\Component\HttpKernel\Event\GetResponseEvent
     */
    protected $event;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    public function __construct(EntityManager $em, ContainerInterface $container, GetResponseEvent $event, UserInterface $user)
    {
        $this->container = $container;
        $this->event = $event;
        $this->user = $user;
        $this->em = $em;
    }

    /**
     * @return bool
     */
    public function isPermissionGranted()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getControllerAlias()
    {
        return str_replace(array("\\", 'Controller', 'Action'), '', $this->event->getRequest()->attributes->get('_controller'));
    }

}