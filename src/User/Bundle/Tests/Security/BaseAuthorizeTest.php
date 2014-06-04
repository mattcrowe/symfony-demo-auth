<?php
namespace User\Bundle\Tests\Security;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseAuthorizeTest extends WebTestCase
{

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    public $em;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    public $container;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->container = static::$kernel->getContainer();
        $this->em = $this->container->get('doctrine')->getManager();
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();
        $this->em->close();
    }

    /**
     * @return \User\Bundle\Entity\User()
     */
    private function setupUser()
    {

        $encoder = new \User\Bundle\Security\BaseEncoder();
        $user = new \User\Bundle\Entity\User();
        $user->setUsername('someguy');
        $user->setSalt('123');
        $user->setPassword($encoder->encodePassword('beerme', $user->getSalt()));

        return $user;
    }

    public function testAll()
    {

        $event = new \Symfony\Component\HttpKernel\Event\GetResponseEvent(
            static::$kernel,
            \Symfony\Component\HttpFoundation\Request::createFromGlobals(),
            1
        );

        $user = $this->setupUser();

        $authorize = new \User\Bundle\Security\BaseAuthorize(
            $this->em,
            $this->container,
            $event,
            $user
        );

        $event->getRequest()->attributes->set(
            '_controller',
            'User\Bundle\Controller\UserController::blaAction'
        );

        $this->assertEquals('UserBundleUser::bla', $authorize->getControllerAlias());
        $this->assertFalse($authorize->isPermissionGranted());
    }

}