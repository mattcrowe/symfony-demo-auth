<?php
namespace User\Bundle\Security;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class BaseProvider implements UserProviderInterface
{

    //configuration
    public $path_login = 'manage_login';
    public $path_logout = 'manage_logout';
    public $path_redirect = 'manage_demo_home';
    public $token_session_key = '_security_manage';
    public $user_class = '\User\Bundle\Entity\User';
    public $encoder_class = '\User\Bundle\Security\BaseEncoder';
    public $token_class = '\User\Bundle\Security\BaseAuthenticateToken';

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * @var BaseEncoder
     */
    protected $encoder;

    /**
     * @var UserInterface
     */
    protected $user;

    public function __construct(EntityManager $em, ContainerInterface $container)
    {
        $this->em = $em;
        $this->container = $container;
        $this->encoder = new $this->encoder_class();
    }

    /**
     * {@inheritdoc }
     */
    public function supportsClass($class)
    {

        return ltrim($class, '\\') === ltrim($this->user_class, '\\');
    }

    /**
     * {@inheritdoc }
     */
    public function loadUserByUsername($username)
    {

        $user = $this->findUserByUsername($username);
        if ($user) {
            $this->setUser($user);
            return $user;
        }

        $this->logout();

        return false;
    }

    /**
     * {@inheritdoc }
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$this->supportsClass(get_class($user))) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * @param null|UserInterface
     * @return BaseProvider
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return null|UserInterface
     */
    public function getUser()
    {
        if (is_null($this->user)) {
            $user = $this->getUserFromSession();
            if ($user) {
                $this->setUser($user);
            }
        }

        return $this->user;
    }

    /**
     * @param string $username
     * @param string $raw_password
     * @return BaseProvider
     */
    public function attemptAuthentication($username, $raw_password)
    {
        $user = $this->findUserByUsername($username);
        if ($user) {
            $encoded_password = $this->encoder->encodePassword($raw_password, $user->getSalt());
            if ($encoded_password == $user->getPassword()) {
                $this->login($user);
            }
        }

        return $this;
    }

    /**
     * @param User
     * @return BaseProvider
     */
    public function login(UserInterface $user)
    {

        $this->setUser($user);
        $token = $this->createToken();
        $token->setUser($user->getUsername());
        $token->setAuthenticated(true);
        $this->container->get('session')->set($this->token_session_key, serialize($token));

        return $this;
    }

    /**
     * @return BaseProvider
     */
    public function logout()
    {
        $this->user = null;
        $this->container->get('session')->remove($this->token_session_key);

        return $this;
    }

    /**
     * @return AbstractToken
     */
    public function createToken()
    {
        return new $this->token_class();
    }

    /**
     * @codeCoverageIgnore
     * @param $username
     * @return bool|UserInterface
     */
    public function findUserByUsername($username)
    {
        return $this->em->getRepository($this->user_class)->findOneBy(array(
            'enabled' => true,
            'username' => $username,
        ));
    }



    /**
     * @return bool|UserInterface
     */
    public function getUserFromSession()
    {

        $token = $this->getToken();
        if ($token) {
            return $this->loadUserByUsername($token->getUsername());
        }

        return false;
    }

    /**
     * @return bool|AbstractToken
     */
    public function getToken()
    {

        $token = $this->container->get('session')->get($this->token_session_key);
        if ($token) {
            return unserialize($token);
        }

        return false;
    }
}