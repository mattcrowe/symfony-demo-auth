<?php
namespace User\Bundle\Tests\Security;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\Collections\ArrayCollection;
use User\Bundle\Entity\Role;
use User\Bundle\Entity\User;
use User\Bundle\Security\BaseAuthenticateToken;
use User\Bundle\Security\BaseAuthenticationProvider;

class BaseAuthenticationProviderTest extends WebTestCase
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

    public function testTest()
    {
        $this->assertTrue(true);
    }

    public function test__construct()
    {
        $authenticationProvider = new BaseAuthenticationProvider($this->em, $this->container);

        $this->assertInstanceOf(
            '\User\Bundle\Security\BaseProvider',
            $authenticationProvider->userProvider
        );

    }

    public function testSupports()
    {
        $authenticationProvider = new BaseAuthenticationProvider($this->em, $this->container);

        $token = new BaseAuthenticateToken();

        $this->assertTrue($authenticationProvider->supports($token));

    }

    public function testAuthenticate()
    {

        $roles = new ArrayCollection(array(
            new Role('ADMIN'),
            new Role('SUPER'),
        ));

        $user = new User();
        $user->setUsername('someguy');
        $user->addRole($roles[0]);
        $user->addRole($roles[1]);

        $authenticationProvider = new BaseAuthenticationProvider($this->em, $this->container);

        $userProvider = $this->getMock('User\Bundle\Security\BaseProvider',
            array('findUserByUsername'),
            array(
                $this->em,
                $this->container
            ));

        //override findUserByUsername to skip DB and return mock user
        $userProvider->expects($this->any())
            ->method('findUserByUsername')
            ->will($this->returnValueMap(array(
                array('someguy', $user),
                array('test', false)
            )));


        $userProvider->logout();

        $authenticationProvider->userProvider = $userProvider;

        $token = new BaseAuthenticateToken($user->getRoles());
        $token->setUser('someguy');

        $result = $authenticationProvider->authenticate($token);

        $this->assertInstanceOf('\User\Bundle\Security\BaseAuthenticateToken', $result);
        $this->assertTrue($result->isAuthenticated());

        $token = new BaseAuthenticateToken($user->getRoles());
        $token->setUser('test');

        $this->setExpectedException('\Symfony\Component\Security\Core\Exception\AuthenticationException');
        $result = $authenticationProvider->authenticate($token);

        $this->assertInstanceOf('\User\Bundle\Security\BaseAuthenticateToken', $result);
        $this->assertFalse($result->isAuthenticated());

    }

}