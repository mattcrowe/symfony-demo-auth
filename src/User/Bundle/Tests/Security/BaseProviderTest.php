<?php
namespace User\Bundle\Tests\Security;

use User\Bundle\Security\BaseProvider;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseProviderTest extends WebTestCase
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
     * @return \User\Bundle\Security\BaseProvider
     */
    private function setupProvider()
    {
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
                array('someguy', $this->setUpUser()),
                array('test', false)
            )));


        $userProvider->logout();

        return $userProvider;
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

    public function testUser()
    {

        //setUser
        $provider = $this->setupProvider();
        $user = $this->setupUser();
        $provider->setUser($user);
        $this->assertInstanceOf('\Symfony\Component\Security\Core\User\UserInterface', $provider->getUser());

        $other_user = clone $user;
        $other_user->setUsername('other');
        $provider->setUser($other_user);
        $this->assertEquals($other_user, $provider->getUser());

        //refreshUser
        $provider->refreshUser($user);
        $this->assertEquals($user, $provider->getUser());

        $user = new TestUser();
        $this->setExpectedException('\Symfony\Component\Security\Core\Exception\UnsupportedUserException');
        $provider->refreshUser($user);
    }

    public function testSupportsClass()
    {
        $provider = $this->setupProvider();
        $this->assertTrue($provider->supportsClass('\User\Bundle\Entity\User'));
        $this->assertFalse($provider->supportsClass('SomeOtherClass'));
    }

    public function testAttemptAuthenticate()
    {

        $provider = $this->setupProvider();
        $user = $this->setupUser();

        //attempt authentication with bad password
        $this->assertEmpty($this->container->get('session')->get('_security_manage'));
        $provider->attemptAuthentication('someguy', 'test');
        $this->assertEmpty($this->container->get('session')->get('_security_manage'));

        //attempt authentication with good password
        $provider->attemptAuthentication('someguy', 'beerme');
        $sessionValue = $this->container->get('session')->get('_security_manage');
        $this->assertNotEmpty($sessionValue);

        //attempt authentication from session
        $provider = $this->setupProvider();
        $this->container->get('session')->set('_security_manage', $sessionValue);
        $this->assertEquals($user, $provider->getUser());

        //load user by bad username (force logout)
        $userLoadedByName = $provider->loadUserByUsername('test');
        $this->assertFalse($userLoadedByName);
        $this->assertEmpty($this->container->get('session')->get('_security_manage'));
    }

    public function testManualAuthenticate()
    {

        $provider = $this->setupProvider();
        $user = $this->setupUser();

        //authenticate
        $this->assertEmpty($this->container->get('session')->get('_security_manage'));
        $provider->login($user);
        $this->assertNotEmpty($this->container->get('session')->get('_security_manage'));

        //get user from session
        $userFromSession = $provider->getUserFromSession();
        $this->assertEquals($user, $userFromSession);

        //load user by username
        $userLoadedByName = $provider->loadUserByUsername('someguy');
        $this->assertEquals($user, $userLoadedByName);

        //logout
        $provider->logout();
        $this->assertEmpty($this->container->get('session')->get('_security_manage'));

        //get user from session when it doesnt exist
        $userFromSession = $provider->getUserFromSession();
        $this->assertFalse($userFromSession);
    }

}