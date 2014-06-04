<?php
namespace User\Bundle\Tests\Security;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use User\Bundle\Security\BaseProvider;
use User\Bundle\Security\BaseAuthorize;
use User\Bundle\Security\BaseAuthenticationListener;
use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;

class BaseAuthenticationListenerTest extends WebTestCase
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
     * @return BaseAuthenticationListener
     */
    private function setupListener()
    {

        $authenticationProviderManager = new AuthenticationProviderManager(
            array($this->setupProvider())
        );

        $listener = new BaseAuthenticationListener($this->em, $this->container, $authenticationProviderManager);

        return $listener;
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
        $user = new \User\Bundle\Entity\User();
        $user->setUsername('someguy');
        $user->setSalt('123');
        $user->setPassword(\User\Bundle\Security\BaseEncoder::encodePassword('beerme', '123'));
        return $user;
    }

    /**
     * @return \User\Bundle\Entity\User()
     */
    private function setupGoodToken()
    {
        $token = new \User\Bundle\Security\BaseAuthenticateToken();
        $token->setUser('someguy');
        $token->setAuthenticated(true);
        return $token;
    }

    /**
     * @return \User\Bundle\Entity\User()
     */
    private function setupBadToken()
    {
        $token = new \User\Bundle\Security\BaseAuthenticateToken();
        $token->setUser('test');
        $token->setAuthenticated(false);
        return $token;
    }

    /**
     * @return GetResponseEvent
     */
    private function setupEvent()
    {
        $event = new \Symfony\Component\HttpKernel\Event\GetResponseEvent(
            static::$kernel,
            \Symfony\Component\HttpFoundation\Request::createFromGlobals(),
            1
        );

        return $event;
    }

    public function ztestBaseProvider()
    {

        $listener = $this->setupListener();

        $this->assertNotEmpty($listener->getUserProvider());
        $this->assertInstanceOf('\User\Bundle\Security\BaseProvider', $listener->getUserProvider());
    }

    public function testAuthorizationManager()
    {

        $user = $this->setupUser();
        $event = $this->setupEvent();
        $listener = $this->setupListener();
        $listener->initAuthorizationManager($event, $user);

        $this->assertNotEmpty($listener->getAuthorizationManager());
        $this->assertInstanceOf('\User\Bundle\Security\BaseAuthorize', $listener->getAuthorizationManager());
    }

    public function testHandle()
    {

        $event = $this->setupEvent();
        $listener = $this->setupListener();
        $provider = $listener->getUserProvider();

        //no token
        $result = $listener->handle($event);
        $this->assertEquals('302', $event->getResponse()->getStatusCode());
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $event->getResponse());

        //token
        $token = $this->setupGoodToken();
        $this->container->get('session')->set($provider->token_session_key, serialize($token));
        $this->container->get('security.context')->setToken($token);

        //handle with token
        $result = $listener->handle($event);
        $this->assertTrue($result);
    }

    public function testHandleBadToken()
    {

        $event = $this->setupEvent();
        $listener = $this->setupListener();
        $provider = $listener->getUserProvider();

        //token
        $token = $this->setupBadToken();
        $this->container->get('session')->set($provider->token_session_key, serialize($token));
        $this->container->get('security.context')->setToken($token);

        //handle with token
        $result = $listener->handle($event);
        $this->assertFalse($result);

        $this->assertEquals('403', $event->getResponse()->getStatusCode());
    }

    public function testHandleOtherBadToken()
    {

        $event = $this->setupEvent();
        $listener = $this->setupListener();
        $provider = $listener->getUserProvider();

        //token
        $token = new TestToken();
        $token->setUser('test');
        $token->setAuthenticated(false);

        $this->container->get('session')->set($provider->token_session_key, serialize($token));
        $this->container->get('security.context')->setToken($token);

        //handle with token
        $result = $listener->handle($event);
        $this->assertFalse($result);

        $this->assertEquals('403', $event->getResponse()->getStatusCode());
    }

}