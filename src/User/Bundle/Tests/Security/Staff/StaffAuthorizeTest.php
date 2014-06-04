<?php
namespace User\Bundle\Tests\Security\Staff;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use User\Bundle\Entity\Role;
use User\Bundle\Entity\RoleObject;
use User\Bundle\Security\Staff\StaffAuthorize;

class StaffAuthorizeTest extends WebTestCase
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
     * @return \User\Bundle\Entity\Staff()
     */
    private function setupUser()
    {

        $encoder = new \User\Bundle\Security\BaseEncoder();
        $user = new \User\Bundle\Entity\Staff();
        $user->setUsername('super');
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

        $authorize = new \User\Bundle\Security\Staff\StaffAuthorize(
            $this->em,
            $this->container,
            $event,
            $user
        );

        $role = new Role('ROLE_TEST');

        $roleObject = new RoleObject();
        $roleObject->setRole($role);
        $roleObject->setObject($authorize->getControllerAlias());

        $authorize = $this->getMock('User\Bundle\Security\Staff\StaffAuthorize',
            array('findRoleObjects'),
            array(
                $this->em,
                $this->container,
                $event,
                $user
            ));

        //override findUserByUsername to skip DB and return mock user
        $authorize->expects($this->any())
            ->method('findRoleObjects')
            ->will($this->returnValue(array(
                $roleObject
            )));

        $event->getRequest()->attributes->set(
            '_controller',
            'User\Bundle\Controller\StaffController::blaAction'
        );

        $this->assertEquals('UserBundleStaff::bla', $authorize->getControllerAlias());
        $this->assertFalse($authorize->isPermissionGranted());

        $user->addRole($role);
        $this->assertTrue($authorize->isPermissionGranted());

        $user->removeRole($role);
        $this->assertFalse($authorize->isPermissionGranted());

        $user->addRole(new Role('ROLE_SUPER'));
        $this->assertTrue($authorize->isPermissionGranted());
    }

}